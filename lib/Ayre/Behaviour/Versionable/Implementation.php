<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Versionable;

use Ayre,
    Ayre\Behaviour,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

trait Implementation
{
    /**
     * @ORM\Column(type="integer")
     */
    protected $version = 1;

    public function createVersion()
    {
    	$version = clone $this->master->versions->last();
    	$this->master->versions->add($version);
    	$version->version++;
        if ($version instanceof Behaviour\Publishable) {
            $version->status = Ayre::STATUS_DRAFT;
        }
    	return $version;
    }
}