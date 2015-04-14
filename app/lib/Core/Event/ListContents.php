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

    public function content($content, $remove = false)
    {
        if ($remove) {
            unset($this->_contents[array_search($content, $this->_contents)]);
        } else {
            $this->_contents[] = $content;
        }
        return $this;
    }
 
    public function contents(array $contents = null)
    {
        if (isset($contents)) {
            foreach ($contents as $content) {
                $this->content($content);
            }
            return $this;
        }
        return $this->_contents;
    }
}