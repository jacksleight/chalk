<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Parser;

use Chalk\Parser;
use DOMDocument;
use DOMXPath;

abstract class Plugin
{
    protected $_parser;

    public function parser(Parser $parser = null)
    {
        if (func_num_args() > 0 && !isset($this->_parser)) {
            $this->_parser = $parser;
            return $this;
        }
        return $this->_parser;
    }

    abstract public function parse(DOMDocument $doc, DOMXPath $xpath);

    abstract public function reverse(DOMDocument $doc, DOMXPath $xpath);
}