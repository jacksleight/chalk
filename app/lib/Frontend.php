<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Coast\App;
use Closure;
use DOMDocument;
use DOMXPath;
use Coast\Request; 
use Coast\Response; 
use Chalk\Core;
use Chalk\Core\Structure\Node;

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
                    $content = $this->em('core_content')->id($data['content']['id']);
                    $el->setAttribute('href', $this->url($content));
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