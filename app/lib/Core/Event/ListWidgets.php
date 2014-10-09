<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Event;

use Chalk\Event;

class ListWidgets extends Event
{
	protected $_widgets = [];

    public function widget($value = null)
    {
        $this->_widgets[] = $value;
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