<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Action extends Model
{
	const TYPE_CREATE			= 'create';
	const TYPE_UPDATE			= 'update';
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
	protected $type;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="create")
     */
    protected $createUser;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\Item", inversedBy="actions")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
	protected $item;
}