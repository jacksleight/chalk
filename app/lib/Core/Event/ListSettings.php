<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Event;

use Chalk\Event;

class ListSettings extends Event
{
	protected $_settings = [];

    public function setting($value = null)
    {
        $this->_settings[] = $value;
        return $this;
    }

    public function settings(array $settings = null)
    {
        if (isset($settings)) {
            foreach ($settings as $value) {
                $this->setting($value);
            }
            return $this;
        }
        return $this->_settings;
    }
}