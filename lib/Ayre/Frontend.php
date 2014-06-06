<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use DOMDocument,
    DOMXPath,
    Coast\Url,
    Coast\App\Request,
    Coast\App\Response,
    Ayre\Core\Content,
    Ayre\Core\Structure\Node;

class Frontend implements \Coast\App\Access, \Coast\App\Executable
{
    use \Coast\App\Access\Implementation;

    protected $_ayre;
    protected $_domain;
    protected $_nodes;
    protected $_paths;
    protected $_contents;

    public function __construct(\Ayre $ayre)
    {
        $this->_ayre = $ayre;
    }

    public function execute(Request $req, Response $res)
    {        
        $this->_domain = $this->_ayre->em('Ayre\Core\Domain')->fetchFirst();
        $nodes = $this->_ayre->em('Ayre\Core\Domain')->fetchNodes($this->_domain);
        foreach ($nodes as $node) {
            $this->_nodes[$node->id]                   = $node;
            $this->_paths[$node->path]                 = $node;
            $this->_contents[$node->contentMaster->id] = $node;
        }

        $path = $req->path();
        if (preg_match('/^_c([\d]+)$/', $path, $match)) {
            $node    = null;
            $content = $match[1];
            if (isset($this->_contents[$content])) {
                return $res->redirect($this->app->url($this->_contents[$content]->path));
            }
            $content = $this->_ayre->em('Ayre\Core\Content')->fetch($content);
            if (!$content) {
                return;
            }
        } else {
            $node = isset($this->_paths[$path])
                ? $this->_paths[$path]
                : null;
            if (!$node) {
                return;
            }
            $content = $node->content;
        }
                
        $req->node    = $node;
        $req->content = $content;
        $method = '_' . \Ayre::type($content)->entity->var;
        return $this->$method($req, $res);
    }

    protected function _page(Request $req, Response $res)
    {
        $html = $this->view->render('index', [
            'req'  => $req,
            'res'  => $res,
            'node' => $req->node,
            'page' => $req->content
        ]);
       
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        // @hack Ensures correct encoding as libxml doesn't understand <meta charset="utf-8">
        $doc->loadHTML('<?xml encoding="utf-8">' . $html);
        libxml_use_internal_errors(false);
        foreach ($doc->childNodes as $node) {
            if ($node->nodeType == XML_PI_NODE) {
                $doc->removeChild($node);
                break;
            }
        }
        $xpath = new DOMXPath($doc);

        $els = $xpath->query('//*[@data-ayre]');
        foreach ($els as $el) {
            $data = json_decode($el->getAttribute('data-ayre'));
            $el->removeAttribute('data-ayre');
            if (!$data) {
                continue;
            }
            if (isset($data->attrs)) {
                foreach ($data->attrs as $name => $args) {
                    $method = array_shift($args);
                    $el->setAttribute($name, call_user_func_array([$this, $method], $args));
                }
            }
        }

        $html = $doc->saveHTML();
        return $res
            ->html($html);
    }

    protected function _file(Request $req, Response $res)
    {
        $file = $req->content->file();
        if (!$file->exists()) {
            return false;
        }
        return $res
            ->redirect($this->app->url->file($file));
    }

    protected function _url(Request $req, Response $res)
    {
        $url = $req->content->url();
        return $res
            ->redirect($url);       
    }

    protected function _route(Request $req, Response $res)
    {
        return;
    }

    public function url($content)
    {
        if ($content instanceof Content) {
            $content = $content->id;
        }
        $node = isset($this->_contents[$content])
            ? $this->_contents[$content]
            : null;
        $path = isset($node)
            ? $node->path
            : "_c{$content}";
        return $this->app->url($path);
    }
}