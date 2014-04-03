<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Log extends Entity
{
	const TYPE_CREATE			= 'create';
	const TYPE_MODIFY			= 'modify';
    const TYPE_STATUS_DRAFT     = 'status_draft';
	const TYPE_STATUS_PENDING	= 'status_pending';
	const TYPE_STATUS_PUBLISHED	= 'status_published';
	const TYPE_STATUS_ARCHIVED	= 'status_archived';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
	protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $class;

    /**
     * @ORM\Column(type="integer")
     */
    protected $class_id;
    
    protected $class_obj;

    /**
     * @ORM\Column(type="string")
     */
	protected $type;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $actionDate;

    /**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\User", inversedBy="actions")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     * @Gedmo\Blameable(on="create")
     */
    protected $user;
}