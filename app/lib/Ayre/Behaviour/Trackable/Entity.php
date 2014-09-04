<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Trackable;

trait Entity
{
    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $createDate;

    /**
     * @ManyToOne(targetEntity="\Ayre\Core\User")
     * @JoinColumn(onDelete="SET NULL")
     */
    protected $createUser;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $modifyDate;

    /**
     * @ManyToOne(targetEntity="\Ayre\Core\User")
     * @JoinColumn(onDelete="SET NULL")
     */
    protected $modifyUser;

    public function createUserName()
    {
        return isset($this->createUser)
            ? $this->createUser->name
            : 'Unknown';
    }

    public function modifyUserName()
    {
        return isset($this->modifyUser)
            ? $this->modifyUser->name
            : 'Unknown';
    }
}