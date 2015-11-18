<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Backend;

use Chalk\App as Chalk;
use Coast\View as CoastView;

class View extends CoastView
{
    public function module($class = null)
    {
        if (!isset($class)) {
            $render = &$this->_active->renders[0];
            $class  = $render->script->group;
        }
        return $this->chalk->module($class);
    }
}