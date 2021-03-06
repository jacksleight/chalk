<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Closure;
use DOMDocument;
use DOMXPath;
use Coast\Request; 
use Coast\Response; 
use Chalk\Chalk;
use Chalk\Entity;
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

    public function children($node, $isIncluded = false, $depth = null, array $params = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->children($node, $isIncluded, $depth, $params);
    }

    public function parents($node, $isIncluded = false, $depth = null, $isReversed = false, array $params = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->parents($node, $isIncluded, $depth, $isReversed, $params);
    }

    public function siblings($node, $isIncluded = false, array $params = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->siblings($node, $isIncluded, $params);
    }

    public function tree($node, $isIncluded = false, $isMerged = false, $depth = null, array $params = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->tree($node, $isIncluded, $isMerged, $depth, $params);
    }

    public function treeIterator($node, $isIncluded = false, $isMerged = false, $depth = null, array $params = array())
    {
        if (!isset($node)) {
            throw new \Exception('Node is not set');
        }
        return $this
            ->em('Chalk\Core\Structure\Node')
            ->treeIterator($node, $isIncluded, $isMerged, $depth, $params);
    }

    public function jump($entity, $id = null)
    {
        $session = $this->session->data('__Chalk\Backend');
        if (!isset($session->user)) {
            return;
        }

        if (isset($id)) {
            $class = $entity;
        } else if (is_object($entity)) {
            $class = get_class($entity);
            $id    = $entity['id'];
        } else if (is_array($entity)) {
            $class = $entity['__CLASS__'];
            $id    = $entity['id'];
        } else {
            $class = $entity;
            $id    = 0;
        }
        $info = Chalk::info($class);
        $url  = $this->backendResolver("core/jump/{$info->name}/{$id}");

        $style = [
            'position: fixed',
            'bottom: 15px',
            'right: 15px',
            'z-index: 999999',
            'margin: 0',
            'border: 0',
            'padding: 0',
            'width: 38px',
            'height: 38px',
        ];
        return '<iframe src="' . $url . '" style="' . implode('; ', $style) . '" allowtransparency="true"></iframe>';
    }
}