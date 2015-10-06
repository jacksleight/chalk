<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Chalk\Parser;
use Closure;
use DOMDocument;
use DOMXPath;

class Parser
{
    protected $_plugins = [];

    public function plugin($name, $plugin = null)
    {
        if (func_num_args() > 0) {
            if (!$plugin instanceof Closure && !$plugin instanceof Plugin) {
                throw new Parser\Exception("Object is not a closure or instance of Chalk\Parser\Plugin");
            }
            $this->_plugins[$name] = $plugin instanceof Closure
                ? $plugin->bindTo($this)
                : $plugin;
            return $this;
        }
        $this->_plugins[$name];
    }

    public function parse($html, $tidy = false)
    {
        $partial = strpos($html, '<!DOCTYPE') === false;

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

        $xpath = new DOMXPath($doc);

        $doc->normalizeDocument();
        $nodes = $xpath->query('.//text()');
        foreach ($nodes as $node) {
            if ($node->isWhitespaceInElementContent() && $node->parentNode) {
                $node->parentNode->removeChild($node);
            }
        }

        foreach ($this->_plugins as $plugin) {
            call_user_func($plugin instanceof Plugin
                ? [$plugin, 'parse']
                : $plugin, $doc, $xpath);
        }

        if ($partial) {
            $frag = new DOMDocument(null, 'utf-8');
            foreach ($doc->getElementsByTagName('body')->item(0)->childNodes as $child) {
                $frag->appendChild($frag->importNode($child, true));
            }
            $doc = $frag;
        }

        if ($tidy) {
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;
        }

        $html = $doc->saveXML($doc, LIBXML_NOEMPTYTAG);
        $html = preg_replace('/<\?xml.*?\?>\n/u', '', $html);
        $html = preg_replace('/<!\[CDATA\[(.*?)\]\]>/us', '$1', $html);
        $html = preg_replace('/<\/(area|base|basefont|br|col|frame|hr|img|input|isindex|link|meta|param)>/u', '', $html);
        $html = preg_replace('/\n(\s+)/u', "\n$1$1", $html);
        return $html;
    }
}