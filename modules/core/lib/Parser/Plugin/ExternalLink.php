<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Parser;

use Chalk\Parser\Plugin;
use DOMDocument;
use DOMXPath;
use DOMText;

class ExternalLink implements Plugin
{
    protected $_localUrls = [];

    protected $_target;

    protected $_className;

    protected $_rel;

    public function __construct(array $options = array())
    {
        foreach ($options as $name => $value) {
            if ($name[0] == '_') {
                throw new \Chalk\Exception("Access to '{$name}' is prohibited");  
            }
            $this->$name($value);
        }
    }

    public function localUrl($localUrl)
    {
        $this->_localUrls[] = $localUrl;
        return $this;
    }

    public function localUrls(array $localUrls = null)
    {
        if (func_num_args() > 0) {
            foreach ($localUrls as $localUrl) {
                $this->localUrl($localUrl);
            }
            return $this;
        }
        return $this->_localUrls;
    }

    public function target($target = null)
    {
        if (func_num_args() > 0) {
            $this->_target = $target;
            return $this;
        }
        return $this->_target;
    }

    public function className($className = null)
    {
        if (func_num_args() > 0) {
            $this->_className = $className;
            return $this;
        }
        return $this->_className;
    }

    public function rel($rel = null)
    {
        if (func_num_args() > 0) {
            $this->_rel = $rel;
            return $this;
        }
        return $this->_rel;
    }

    public function parse(DOMDocument $doc, DOMXPath $xpath)
    {
        $exprs = [];
        foreach ($this->_localUrls as $localUrl) {
            $exprs[] = "not(starts-with(@href, '{$localUrl}'))";
        }
        $expr  = "//a[(starts-with(@href, 'http://') or starts-with(@href, 'https://')) and " . implode(' and ', $exprs) . "]";
        $nodes = $xpath->query($expr);
        foreach ($nodes as $node) {
            if (isset($this->_className)) {
                $node->setAttribute('class', $node->getAttribute('class')
                    ? "{$node->getAttribute('class')} {$this->_className}"
                    : "{$this->_className}");
            }
            if (isset($this->_target)) {
                $node->setAttribute('target', $this->_target);
            }
            if (isset($this->_rel)) {
                $node->setAttribute('rel', $this->_rel);
            }
        }
    }
}