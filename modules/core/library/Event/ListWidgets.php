<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Event;

use Chalk\Event;

class ListWidgets extends Event
{
	protected $_widgets = [];

    public function widget($widget, $remove = false)
    {
        if ($remove) {
            unset($this->_widgets[array_search($widget, $this->_widgets)]);
        } else {
            $this->_widgets[] = $widget;
        }
        return $this;
    }
 
    public function widgets(array $widgets = null)
    {
        if (isset($widgets)) {
            foreach ($widgets as $value) {
                $this->widget($value);
            }
            return $this;
        }
        return $this->_widgets;
    }
}