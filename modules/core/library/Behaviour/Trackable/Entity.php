<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Trackable;

use Chalk\Entity as ChalkEntity;

trait Entity
{
    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $createDate;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\User")
     * @JoinColumn(onDelete="SET NULL")
     */
    protected $createUser;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $updateDate;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\User")
     * @JoinColumn(onDelete="SET NULL")
     */
    protected $updateUser;

    public function createUserName()
    {
        return isset($this->createUser)
            ? $this->createUser->name
            : 'System';
    }

    public function updateUserName()
    {
        return isset($this->updateUser)
            ? $this->updateUser->name
            : 'System';
    }

    protected function _trackable_duplicate(ChalkEntity $entity)
    {
        $entity->fromArray([
            'createDate'  => null,
            'updateDate'  => null,
            'createUser'  => null,
            'updateUser'  => null,
        ]);
    }
}