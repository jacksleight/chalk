<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Parser\Plugin;

use Chalk\Parser\Plugin;
use DOMDocument;
use DOMXPath;
use DOMText;

class ReplaceText implements Plugin
{
    protected $_texts = [];

    public function __construct(array $options = array())
    {
        foreach ($options as $name => $value) {
            if ($name[0] == '_') {
                throw new \Chalk\Exception("Access to '{$name}' is prohibited");  
            }
            $this->$name($value);
        }
    }

    public function text($source, $target = null)
    {
        if (func_num_args() > 0) {
            $this->_texts[$source] = $target;
            return $this;
        }
        return isset($this->_texts[$source])
            ? $this->_texts[$source]
            : null;
    }

    public function texts(array $texts = null)
    {
        if (func_num_args() > 0) {
            foreach ($texts as $source => $target) {
                $this->text($source, $target);
            }
            return $this;
        }
        return $this->_texts;
    }

    public function parse(DOMDocument $doc, DOMXPath $xpath)
    {
        $nodes = $xpath->query('.//text()');
        foreach ($nodes as $node) {
            $input  = $node->textContent;
            $output = str_replace(array_keys($this->_texts), array_values($this->_texts), $input);
            if ($input !== $output) {
                $replace = new DOMText($output);
                $node->parentNode->replaceChild($replace, $node);
            }
        }
    }
}