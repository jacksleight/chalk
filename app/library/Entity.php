<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Chalk\Chalk;

abstract class Entity extends \Toast\Entity
{
	public static $chalkSingular = null;
    public static $chalkPlural   = null;
    public static $chalkText     = null;
    public static $chalkGroup    = null;
	public static $chalkIcon     = null;

    public function previewName()
    {
        return property_exists($this, 'name')
            ? $this->name
            : null;
    }

    public function previewText($context = false)
    {
        $parts = [];
        if (!$context) {
            array_unshift($parts, $this->typeLabel);
        }
        return $parts;
    }

    public function previewFile()
    {}

    public function type()
    {
        return Chalk::info($this)->name;
    }

    public function typeIcon()
    {
        return Chalk::info($this)->icon;
    }

    public function typeLabel()
    {
        return static::staticTypeLabel(get_class($this));
    }

    public static function staticTypeLabel($type)
    {
        return Chalk::info($type)->singular;
    }
}