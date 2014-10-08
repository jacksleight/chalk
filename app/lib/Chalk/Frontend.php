<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use DOMDocument,
    DOMXPath,
    Coast\Url,
    Coast\Request,
    Coast\Response,
    Chalk\Core\Content,
    Chalk\Core\Structure\Node;

class Frontend implements \Coast\App\Access, \Coast\App\Executable
{
    use \Coast\App\Access\Implementation;

    protected $_chalk;
    protected $_domain;
    protected $_nodes;
    protected $_paths;
    protected $_contents;

    public function __construct(\Chalk $chalk)
    {
        $this->_chalk = $chalk;
    }

    public function execute(Request $req, Response $res)
    {        
        $this->_domain = $this->_chalk->em('Chalk\Core\Domain')->fetchFirst();
        $nodes = $this->_chalk->em('Chalk\Core\Structure')->fetchNodes($this->_domain->structure);
        foreach ($nodes as $node) {
            $this->_nodes[$node->id]             = $node;
            $this->_paths[$node->path]           = $node;
            $this->_contents[$node->content->id] = $node;
        }

        $path = rtrim($req->path(), '/');
        if (preg_match('/^_c([\d]+)$/', $path, $match)) {
            $node    = null;
            $content = $match[1];
            if (isset($this->_contents[$content])) {
                return $res->redirect($this->app->url($this->_contents[$content]->path));
            }
            $content = $this->_chalk->em('Chalk\Core\Content')->id($content);
            if (!$content) {
                return;
            }
        } else {
            $node = isset($this->_paths[$path])
                ? $this->_paths[$path]
                : null;
            if (isset($node) && $req->path() != $node->path) {
                return $res->redirect($this->app->url($node->path));
            }
            if (!$node) {
                return;
            }
            $content = $node->content;
        }
                
        $req->node    = $node;
        $req->content = $content;
        $method = '_' . \Chalk::entity($content)->local->var;
        return method_exists($this, $method)
            ? $this->$method($req, $res)
            : $this->_render($req, $res, \Chalk::entity($content));
    }

    protected function _render(Request $req, Response $res, $entity)
    {
        $html = $this->view->render('chalk/' . $entity->path, [
            'req'               => $req,
            'res'               => $res,
            'node'              => $req->node,
            $entity->local->var => $req->content
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

        $els = $xpath->query('//*[@data-chalk-widget]');
        foreach ($els as $el) {
            $data = json_decode($el->getAttribute('data-chalk-widget'), true);
            $el->removeAttribute('data-chalk-widget');
            if (!$data) {
                continue;
            }
            $entity = \Chalk::entity($data['entity']);
            $class  = $entity->class;
            $widget = (new $class())->fromArray($data['params']);
            $temp   = new DOMDocument();
            libxml_use_internal_errors(true);
            $temp->loadHTML('<?xml encoding="utf-8">' . $this->render('chalk/' . $entity->path, ['widget' => $widget]));
            libxml_use_internal_errors(false);
            $body = $temp->getElementsByTagName('body');
            if ($body->length) {
                $nodes = $body->item(0)->childNodes;
                for ($i = $nodes->length - 1; $i >= 0; --$i) {
                    $node = $doc->importNode($nodes->item($i), true);
                    $el->parentNode->insertBefore($node, $el);
                }
            }
            $el->parentNode->removeChild($el);
        }

        $els = $xpath->query('//*[@data-chalk]');
        foreach ($els as $el) {
            $data = json_decode($el->getAttribute('data-chalk'), true);
            $el->removeAttribute('data-chalk');
            if (!$data) {
                continue;
            }
            if (isset($data['attrs'])) {
                foreach ($data['attrs'] as $name => $args) {
                    $el->setAttribute($name, $this->_parse($args));
                }
            }
            if (isset($data['html'])) {
                $temp = new DOMDocument();
                libxml_use_internal_errors(true);
                $temp->loadHTML('<?xml encoding="utf-8">' . $this->_parse($data['html']));
                libxml_use_internal_errors(false);
                $body = $temp->getElementsByTagName('body');
                if ($body->length) {
                    $nodes = $body->item(0)->childNodes;
                    for ($i = $nodes->length - 1; $i >= 0; --$i) {
                        $node = $doc->importNode($nodes->item($i), true);
                        $el->parentNode->insertBefore($node, $el);
                    }
                }
                $el->parentNode->removeChild($el);
            }
        }

        $html = $doc->saveHTML();
        $html = str_replace('<p>&nbsp;</p>', '', $html);
        return $res
            ->html($html);
    }

    protected function _parse($args)
    {
        $method = array_shift($args);
        return call_user_func_array([$this, $method], $args);
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

    protected function _alias(Request $req, Response $res)
    {
        $content = $req->content->content();
        return $res
            ->redirect($this->url($content));
    }

    protected function _url(Request $req, Response $res)
    {
        $url = $req->content->url();
        return $res
            ->redirect($url);       
    }

    protected function _blank(Request $req, Response $res)
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

    public function render($name, array $params = array(), $set = null)
    {
        return $this->app->view->render($name, $params, $set);
    }
}