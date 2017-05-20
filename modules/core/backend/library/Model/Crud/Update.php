<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Model\Crud;

use Chalk\Chalk;
use Chalk\Core\Backend\Model;
use	Doctrine\Common\Collections\ArrayCollection;

class Update extends Model
{
    public function tagsPlus($tag)
    {
        return [$tag];
    }

    public function tagsMinus($tag)
    {
        return $this->tags();
    }

    public function tagsToggle($tag)
    {
        if ($tag == 'none') {
            return [$tag];
        }
        return parent::tagsToggle($tag);
    }
}
