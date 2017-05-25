<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Parser\Plugin;

use Chalk\Parser\Plugin;
use DOMDocument;
use DOMXPath;
use DOMText;

class StripEmpty extends Plugin
{
    protected $_tags = [];

    public function __construct(array $options = array())
    {
        foreach ($options as $name => $value) {
            if ($name[0] == '_') {
                throw new \Chalk\Exception("Access to '{$name}' is prohibited");  
            }
            $this->$name($value);
        }
    }

    public function tag($tag = null)
    {
        $this->_tags[] = $tag;
        return $this;
    }

    public function tags(array $tags = null)
    {
        if (func_num_args() > 0) {
            foreach ($tags as $tag) {
                $this->tag($tag);
            }
            return $this;
        }
        return $this->_tags;
    }

    public function parse(DOMDocument $doc, DOMXPath $xpath)
    {
        $exprs = [];
        foreach ($this->_tags as $tag) {
            $exprs[] = "self::{$tag}";
        }
        $expr  = "//*[" . implode(' or ', $exprs) . "]";
        $nodes = $xpath->query($expr);
        foreach ($nodes as $node) {
            if ($node->firstChild && (
                $node->firstChild !== $node->lastChild ||
                $node->firstChild->nodeType != XML_TEXT_NODE ||
                $node->firstChild->textContent != \Coast\str_uchr('nbsp'))) {
                continue;
            }
            $node->parentNode->removeChild($node);
        }
    }
    
    public function reverse(DOMDocument $doc, DOMXPath $xpath)
    {}
}