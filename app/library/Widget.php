<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Chalk\Chalk;

abstract class Widget extends \Toast\Entity
{
    const STATE_NEW   = 'new';
    const STATE_SAVED = 'saved';

    public static $chalkSingular = null;
    public static $chalkPlural   = null;
    public static $chalkText     = null;
    public static $chalkGroup    = null;
    public static $chalkIcon     = 'stack';

    protected $_state;

    public function __construct($state = self::STATE_SAVED)
    {
        $this->_state = $state;
    }

    public function isNew()
    {
        return $this->_state == self::STATE_NEW;
    }

    public function isSaved()
    {
        return $this->_state == self::STATE_SAVED;
    }

    public function previewName()
    {
        return Chalk::info(get_class($this))->singular;
    }

    public function previewText($context = false)
    {
        $parts = [];
        return $parts;
    }

    public function previewFile()
    {}

    public function renderParams()
    {
        return $this->toArray();
    }
}