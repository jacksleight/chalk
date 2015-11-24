<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Closure;
use DOMDocument;
use DOMXPath;
use Coast\Request; 
use Coast\Response; 
use Chalk\App as Chalk;
use Chalk\Core;
use Chalk\Core\Structure\Node;

class Frontend extends \Coast\App
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

    public function date(\DateTime $date)
    {
        $date->setTimezone(new \DateTimezone($this->chalk->config->timezone));
        return $date;
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