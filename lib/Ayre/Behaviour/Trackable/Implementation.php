<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Trackable;

use Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

trait Implementation
{
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Gedmo\Blameable(on="create")
     */
    protected $createUser;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $modifyDate;

    /**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\User")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Gedmo\Blameable(on="update")
     */
    protected $modifyUser;

    protected function _alterCreateDateMetadata($md)
    {
        $md['validator']->removeValidator('Js\Validator\Set');
        return $md;
    }

    protected function _alterModifyDateMetadata($md)
    {
        $md['validator']->removeValidator('Js\Validator\Set');
        return $md;
    }
}