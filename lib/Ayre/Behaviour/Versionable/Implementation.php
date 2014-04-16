<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Versionable;

use Ayre,
    Ayre\Behaviour\Publishable,
    Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

trait Implementation
{
    /**
     * @ORM\Column(type="integer")
     */
    protected $version = 1;

    protected $master;

    protected $previous;

    protected $next;

    protected $versions;

    public function __construct()
    {   
        $this->versions = new ArrayCollection();
        
        $this->master = $this;
        $this->versions->add($this);
    }

    public function createVersion()
    {
    	$version = clone $this->master->versions->last();
    	$this->master->versions->add($version);
        $version->previous = $this;
        $this->next = $version;
    	
        $version->id = null;
        $version->version++;
        if ($version instanceof Publishable) {
            $version->status = Ayre::STATUS_DRAFT;
        }
    	return $version;
    }

    public function isMaster()
    {
        return $this === $this->master;
    }

    public function isOldest()
    {
        return !isset($this->previous);
    }

    public function isNewest()
    {
        return !isset($this->next);
    }
}