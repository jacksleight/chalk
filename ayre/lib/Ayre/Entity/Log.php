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
class Log extends \Toast\Entity
{
	const TYPE_CREATE			= 'create';
	const TYPE_MODIFY			= 'modify';
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
    protected $entity_class;

    /**
     * @ORM\Column(type="integer")
     */
    protected $entity_id;
    
    protected $entity_obj;

    /**
     * @ORM\Column(type="string")
     */
	protected $type;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $logDate;

    /**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\User", inversedBy="logs")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @Gedmo\Blameable(on="create")
     */
    protected $user;
}