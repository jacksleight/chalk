<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\App as Chalk,
	Toast\Entity;

abstract class Widget extends Entity
{
    public static $chalkIcon = 'stack';

    public function previewText($parts = [])
    {   
        return $parts;
    }

    public function previewFile()
    {}
}