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
    protected function _render()
    {
        $render = &$this->_active->renders[0];
        $this->app->module = $this->chalk->module($render->script->group);
        return parent::_render();
    }
}