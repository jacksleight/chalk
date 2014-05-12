<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Versionable;

use Ayre,
    Ayre\Behaviour\Publishable,
    Doctrine\Common\Collections\ArrayCollection;

trait Implementation
{
    /**
     * @Column(type="integer")
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

    public function duplicate()
    {
        if (!$this->isCurrent()) {
            throw new \Ayre\Exception('You can only create new versions from the current version');
        }

        $version = clone $this;
        $this->master->versions->add($version);
        $version->previous = $this;
        $this->next = $version;

        $version->id = null;
        $version->next = null;
        $version->version++;
        if ($version instanceof Publishable) {
            $version->status = Ayre::STATUS_PENDING;
        }
        return $version;
    }

    public function restore()
    {
        $last = $this->master->versions->last();
       
        $version = clone $this;
        $this->master->versions->add($version);
        $version->previous = $last;
        $last->next = $version;

        $version->id = null;
        $version->next = null;
        $version->version = $last->version + 1;
        if ($version instanceof Publishable) {
            $version->status = Ayre::STATUS_PENDING;
        }

        return $version;
    }

    public function first()
    {
        return $this->master->versions->first();
    }

    public function last()
    {
        return $this->master->versions->last();
    }

    public function isMaster()
    {
        return !isset($this->previous);
    }

    public function isCurrent()
    {
        return !isset($this->next);
    }

    public function isPending()
    {
        return $this->status == Ayre::STATUS_PENDING;
    }

    public function isPublished()
    {
        return $this->status == Ayre::STATUS_PUBLISHED;
    }

    public function isArchived()
    {
        return $this->status == Ayre::STATUS_ARCHIVED;
    }

    public function isNewMaster()
    {
        return $this->isNew() && $this->isMaster();
    }
}