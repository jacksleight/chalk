<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Event;

use Chalk\Event;

class ListContents extends Event
{
	protected $_contents = [];

    public function content($value = null)
    {
        $key = count($this->_contents);
        foreach ($this->_contents as $i => $content) {
            if (is_subclass_of($value, $content)) {
                $key = $i;
                break;
            }
        }
        $this->_contents[$key] = $value;
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