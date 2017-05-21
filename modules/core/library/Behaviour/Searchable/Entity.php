<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Searchable;

use Chalk\Chalk;
use Doctrine\Common\Collections\ArrayCollection;

trait Entity
{           
    public function searchContent(array $content = array())
    {
        return $content;
    }
}