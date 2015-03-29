<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Coast\App,
    Closure,
    DOMDocument,
    DOMXPath,
    Coast\Request, 
    Coast\Response, 
    Chalk\Core,
    Chalk\Core\Structure\Node;

class Frontend extends App
{
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
        $req->chalk = (object) [];

        $conn  = $this->em->getConnection();
        $name = Chalk::info('Chalk\Core')->name;

        $domain = $this
            ->em('Chalk\Core\Domain')
            ->one([], 'id');     
        $req->chalk->domain = $domain;
        $req->chalk->root   = $domain->structures->first()->root;
        $req->chalk->home   = $domain->structures->first()->root->content;

        $nodes = $conn->query("
            SELECT n.id,
                n.sort, n.left, n.right, n.depth,
                n.name, n.slug, n.path,
                n.structureId,
                n.parentId,
                n.contentId
            FROM {$name}_structure_node AS n
                INNER JOIN {$name}_structure AS s ON s.id = n.structureId
                INNER JOIN {$name}_domain__core_structure AS d ON d.core_structureId = s.id
            WHERE d.core_domainId = {$domain->id}
        ")->fetchAll();
        $map = [];
        foreach ($nodes as $node) {
            $map[$node['id']] = $node;
            $this->router->all($node['contentId'], $node['path'], [
                'node'    => $node,
                'content' => $node['contentId'],
            ]);
        }

        $path = rtrim($req->path(), '/');        
        if (preg_match('/^_c([\d]+)$/', $path, $match)) {
            $node    = null;
            $content = $match[1];
        } else {
            $route = $this->router->match($req->method(), $path);
            if (!$route) {
                return;
            }
            if ($route['path'] != $req->path()) {
                return $res->redirect($this->url($route['path']) . ($req->queryParams() ? $this->url->query($req->queryParams()) : null));
            }
            $node    = $route['params']['node'];
            $content = $route['params']['content'];
        }
        $nodes = [$node];
        while (isset($nodes[0]['parentId'])) {
            array_unshift($nodes, $map[$nodes[0]['parentId']]);
        }
        $content = $this->em('Chalk\Core\Content')->id($content);
        $req->chalk->node    = $node;
        $req->chalk->nodes   = $nodes;
        $req->chalk->content = $content;

        if (!$content) {
            return;
        }

        $name = \Chalk\Chalk::info($content)->class;
        if (!isset($this->_handlers[$name])) {
            throw new \Exception("No handler exists for '{$name}' content");
        }
        $hander = $this->_handlers[$name];
        
        $this->param('req', $req)
             ->param('res', $res);
        $result = $hander($req, $res);
        $this->param('req', null)
             ->param('res', null);

        return $result;
    }

    public function parse($html)
    {       
        $doc   = $this->_htmlToDom($html);
        $xpath = new DOMXPath($doc);

        $els = $xpath->query('//*[@data-chalk]');
        while ($els->length) {
            foreach ($els as $el) {
                $data = json_decode($el->getAttribute('data-chalk'), true);
                $el->removeAttribute('data-chalk');
                if (!$data) {
                    continue;
                }
                if (isset($data['content'])) {
                    if ($this->router->has($data['content']['id'])) {
                        $url = $this->url([], $data['content']['id'], true);
                    } else {
                        $url = $this->url("_c{$data['content']['id']}");
                    }
                    $el->setAttribute('href', $url);
                } else if (isset($data['widget'])) {
                    $info   = \Chalk\Chalk::info($data['widget']['name']);
                    $class  = $info->class;
                    $widget = (new $class())->fromArray($data['widget']['params']);
                    $html   = $this->view->render('chalk/' . $info->module->path . '/' . $info->local->path, $widget->toArray());
                    $temp   = $this->_htmlToDom($html);
                    $body   = $temp->getElementsByTagName('body');
                    if ($body->length) {
                        $nodes = $body->item(0)->childNodes;
                        for ($i = 0; $i < $nodes->length; $i++) {
                            $node = $doc->importNode($nodes->item($i), true);
                            $el->parentNode->insertBefore($node, $el);
                        }
                    }
                    $el->parentNode->removeChild($el);
                }
            }
            $els = $xpath->query('//*[@data-chalk]');
        } 

        // Remove empty paragraphs
        $els = $xpath->query('//p');
        foreach ($els as $el) {
            if ($el->firstChild && (
                $el->firstChild !== $el->lastChild ||
                $el->firstChild->nodeType != XML_TEXT_NODE ||
                $el->firstChild->textContent != \Coast\str_uchr('nbsp'))) {
                continue;
            }
            $el->parentNode->removeChild($el);
        }
        return $doc->saveHTML();
    }

    protected function _htmlToDom($html)
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        // @hack Ensures correct encoding as libxml doesn't understand <meta charset="utf-8">
        $doc->loadHTML('<?xml encoding="utf-8">' . $html, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        libxml_use_internal_errors(false);
        foreach ($doc->childNodes as $node) {
            if ($node->nodeType == XML_PI_NODE) {
                $doc->removeChild($node);
                break;
            }
        }
        return $doc;
    }

    public function children($node, $isIncluded = false, $depth = null, array $criteria = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->children($node, $isIncluded, $depth, $criteria);
    }

    public function parents($node, $isIncluded = false, $depth = null, $isReversed = false, array $criteria = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->parents($node, $isIncluded, $depth, $isReversed, $criteria);
    }

    public function siblings($node, $isIncluded = false, array $criteria = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->siblings($node, $isIncluded, $criteria);
    }

    public function tree($node, $isIncluded = false, $isMerged = false, $depth = null, array $criteria = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->tree($node, $isIncluded, $isMerged, $depth, $criteria);
    }

    public function treeIterator($node, $isIncluded = false, $isMerged = false, $depth = null, array $criteria = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->treeIterator($node, $isIncluded, $isMerged, $depth, $criteria);
    }
}