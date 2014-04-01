<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Versionable;

use Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

trait Implementation
{
    /**
     * @ORM\Column(type="integer")
     */
    protected $version = 1;
}