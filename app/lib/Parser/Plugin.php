<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Parser;

use DOMDocument;
use DOMXPath;

interface Plugin
{
    public function parse(DOMDocument $doc, DOMXPath $xpath);

    public function reverse(DOMDocument $doc, DOMXPath $xpath);
}