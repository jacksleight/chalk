<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Backend;

use Chalk\Chalk;
use Coast\View as CoastView;

class View extends CoastView
{
    protected function _render()
    {
        $prev   = $this->module;
        $render = &$this->_active->renders[0];
        $this->module = $this->chalk->module($render->script->group);
        $return = parent::_render();
        $this->module = $prev;
        return $return;
    }
}