<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Coast\App\Executable;
use Coast\App\Access;
use Chalk\Parser;
use Chalk\Parser\Plugin;
use Coast\Request;
use Coast\Response;
use Closure;
use DOMDocument;
use DOMXPath;

class Parser implements Access, Executable
{
    use Access\Implementation;
    use Executable\Implementation;

    protected $_isTidy = false;

    protected $_plugins = [];

    public function htmlToDoc($html)
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        // Ensures correct encoding as libxml doesn't understand <meta charset="utf-8">
        $doc->loadHTML('<?xml encoding="utf-8">' . $html);
        libxml_use_internal_errors(false);
        foreach ($doc->childNodes as $node) {
            if ($node->nodeType == XML_PI_NODE) {
                $doc->removeChild($node);
                break;
            }
        }
        return $doc;
    }

    public function docToHtml(DOMDocument $doc)
    {
        $html = $doc->saveXML($doc, LIBXML_NOEMPTYTAG);
        $html = preg_replace('/<\?xml.*?\?>\n/u', '', $html);
        $html = preg_replace('/<!\[CDATA\[(.*?)\]\]>/us', '$1', $html);
        $html = preg_replace('/<\/(area|base|basefont|br|col|frame|hr|img|input|isindex|link|meta|param)>/u', '', $html);
        $html = preg_replace('/\n(\s+)/u', "\n$1$1", $html);
        return $html;
    }

    public function __construct(array $options = array())
    {
        foreach ($options as $name => $value) {
            if ($name[0] == '_') {
                throw new \Chalk\Exception("Access to '{$name}' is prohibited");  
            }
            $this->$name($value);
        }
    }

    public function isTidy($isTidy = null)
    {
        if (func_num_args() > 0) {
            $this->_isTidy = (bool) $isTidy;
            return $this;
        }
        return $this->_isTidy;
    }

    public function plugin($name, Plugin $plugin = null)
    {
        if (func_num_args() > 0) {
            if ($plugin instanceof Access) {
                $plugin->app($this->app());
            }
            $this->_plugins[$name] = $plugin;
            return $this;
        }
        $this->_plugins[$name];
    }

    public function parse($html)
    {
        return $this->_run($html, 'parse');
    }

    public function reverse($html)
    {
        return $this->_run($html, 'reverse');
    }

    protected function _run($html, $method)
    {
        if (!$html) {
            return $html;
        }

        $partial = strpos($html, '<!DOCTYPE') === false;

        $doc = $this->htmlToDoc($html);

        $xpath = new DOMXPath($doc);

        $doc->normalizeDocument();
        $nodes = $xpath->query('.//text()');
        foreach ($nodes as $node) {
            if ($node->isWhitespaceInElementContent() && $node->parentNode) {
                $node->parentNode->removeChild($node);
            }
        }

        foreach ($this->_plugins as $plugin) {
            call_user_func([$plugin, $method], $doc, $xpath);
        }

        if ($partial) {
            $frag = new DOMDocument(null, 'utf-8');
            foreach ($doc->getElementsByTagName('body')->item(0)->childNodes as $child) {
                $frag->appendChild($frag->importNode($child, true));
            }
            $doc = $frag;
        }

        if ($this->_isTidy) {
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;
        }

        return $this->docToHtml($doc);        
    }

    public function postExecute(Request $req, Response $res)
    {
        if (strpos($res->header('content-type'), 'text/html') !== 0) {
            return;
        }
        $res->body($this->parse($res->body()));
    }
}