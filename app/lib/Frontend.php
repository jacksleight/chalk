<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use DOMDocument,
    DOMXPath,
    Closure,
    Coast\Url,
    Coast\App\Router,
    Coast\App\UrlResolver,
    Coast\Request,
    Coast\Response,
    Chalk\Chalk,
    Chalk\Core\Content,
    Chalk\Core\Structure\Node;

class Frontend implements \Coast\App\Access, \Coast\App\Executable
{
    use \Coast\App\Access\Implementation;

    protected $_chalk;
    protected $_router;
    protected $_domain;
    protected $_handlers = [];

    public function __construct(Chalk $chalk)
    {
        $this->_chalk = $chalk;
        $this->_router = new Router();
    }

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
        $this->_domain = $this->_chalk
            ->em('Chalk\Core\Domain')
            ->fetchFirst();
        $nodes = $this->_chalk
            ->em('Chalk\Core\Structure')
            ->fetchNodes($this->_domain->structure);
        foreach ($nodes as $node) {
            $this->_router->all($node->content->id, $node->path, [
                'node'    => $node,
                'content' => $node->content,
            ]);
        }

        $path = rtrim($req->path(), '/');        
        if (preg_match('/^_c([\d]+)$/', $path, $match)) {
            $node    = null;
            $content = $match[1];
            if ($this->_router->has($content)) {
                $route = $this->_router->get($content);
                return $res->redirect($this->app->rootUrl($route['path']));
            }
            $content = $this->_chalk->em('Chalk\Core\Content')->id($content);
            if (!$content) {
                return;
            }
        } else {
            $route = $this->_router->match($req->method(), $path);
            if (!$route) {
                return;
            }
            if ($route['path'] != $req->path()) {
                return $res->redirect($this->app->rootUrl($route['path']));
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
            $entity = \Chalk\Chalk::entity($data['entity']);
            $class  = $entity->class;
            $widget = (new $class())->fromArray($data['params']);
            $temp   = new DOMDocument();
            libxml_use_internal_errors(true);
            $temp->loadHTML('<?xml encoding="utf-8">' . $this->render('chalk/' . $entity->module->path . '/' . $entity->local->path, $widget->toArray()));
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
                    $el->setAttribute($name, $this->_resolve($args));
                }
            }
            if (isset($data['html'])) {
                $temp = new DOMDocument();
                libxml_use_internal_errors(true);
                $temp->loadHTML('<?xml encoding="utf-8">' . $this->_resolve($data['html']));
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
        return $html;
    }

    protected function _resolve($args)
    {
        $method = array_shift($args);
        return call_user_func_array([$this, $method], $args);
    }

    public function url($content)
    {
        if ($content instanceof Content) {
            $content = $content->id;
        }
        $route = $this->_router->has($content)
            ? $this->_router->route($content)
            : null;
        $path = isset($route)
            ? $route['path']
            : "_c{$content}";
        return $this->app->rootUrl($path);
    }

    public function render($name, array $params = array(), $set = null)
    {
        return $this->app->view->render($name, $params, $set);
    }
}