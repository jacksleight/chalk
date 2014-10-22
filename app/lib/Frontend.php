<?php
namespace Chalk;

use Coast\App,
    Closure,
    DOMDocument,
    DOMXPath,
    Coast\Request, 
    Coast\Response, 
    Chalk\Core;

class Frontend extends App
{
    protected $_domain;
    protected $_handlers = [];

    public function handler($name, Closure $value = null)
    {
        if (func_num_args() > 1) {
            $this->_handlers[$name] = $value->bindTo($this);
            return $this;
        }
        return isset($this->_handlers[$name])
            ? $this->_handlers[$name]
            : null;
    }

    public function handlers(array $handlers = null)
    {
        if (func_num_args() > 0) {
            foreach ($handlers as $name => $value) {
                $this->handler($name, $value);
            }
            return $this;
        }
        return $this->_handlers;
    }

    public function execute(Request $req, Response $res)
    {        
        $this->_domain = $this
            ->em('Chalk\Core\Domain')
            ->fetchFirst();
        $nodes = $this
            ->em('Chalk\Core\Structure')
            ->fetchNodes($this->_domain->structure);
        foreach ($nodes as $node) {
            $this->router->all($node->content->id, $node->path, [
                'node'    => $node,
                'content' => $node->content,
            ]);
        }

        $path = rtrim($req->path(), '/');        
        if (preg_match('/^_c([\d]+)$/', $path, $match)) {
            $node    = null;
            $content = $match[1];
            if ($this->router->has($content)) {
                $route = $this->router->route($content);
                return $res->redirect($this->url($route['path']));
            }
            $content = $this->em('Chalk\Core\Content')->id($content);
            if (!$content) {
                return;
            }
        } else {
            $route = $this->router->match($req->method(), $path);
            if (!$route) {
                return;
            }
            if ($route['path'] != $req->path()) {
                return $res->redirect($this->url($route['path']));
            }
            $node    = $route['params']['node'];
            $content = $route['params']['content'];
        }
                
        $req->node    = $node;
        $req->content = $content;

        $name = \Chalk\Chalk::entity($content)->class;
        if (!isset($this->_handlers[$name])) {
            throw new \Exception("No handler exists for '{$name}' content");
        }
        $hander = $this->_handlers[$name];
        return $hander($req, $res);
    }

    public function parse($html)
    {       
        $doc = $this->_htmlToDom($html);
        $els = (new DOMXPath($doc))->query('//*[@data-chalk]');
        foreach ($els as $el) {
            $data = json_decode($el->getAttribute('data-chalk'), true);
            $el->removeAttribute('data-chalk');
            if (!$data) {
                continue;
            }
            if (isset($data['content'])) {
                $el->setAttribute('href', $this->url($data['content']['id']));
            } else if (isset($data['widget'])) {
                $entity = \Chalk\Chalk::entity($data['widget']['name']);
                $class  = $entity->class;
                $widget = (new $class())->fromArray($data['widget']['params']);
                $html   = $this->view->render('chalk/' . $entity->module->path . '/' . $entity->local->path, $widget->toArray());
                $temp   = $this->_htmlToDom($html);
                $body   = $temp->getElementsByTagName('body');
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
        return $doc->saveHTML();
    }

    protected function _htmlToDom($html)
    {
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
        return $doc;
    }
}