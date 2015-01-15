<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Event;

use Chalk\Event;

class ListContents extends Event
{
	protected $_contents = [];

    public function content($value, $replace = null)
    {
        if (isset($replace)) {
            $this->_contents[array_search($replace, $this->_contents)] = $value;
        } else {
            $this->_contents[] = $value;            
        }
        return $this;
    }

    public function contents(array $contents = null)
    {
        if (isset($contents)) {
            foreach ($contents as $value) {
                $this->content($value);
            }
            return $this;
        }
        return $this->_contents;
    }
}