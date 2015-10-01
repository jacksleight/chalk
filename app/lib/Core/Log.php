<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core,
    Carbon\Carbon,
    Chalk\Behaviour\Trackable,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Log extends \Toast\Entity implements Trackable
{
    public static $chalkSingular = 'Log';
    public static $chalkPlural   = 'Logs';

    use Trackable\Entity;

	const TYPE_CREATE			= 'create';
	const TYPE_MODIFY			= 'modify';
	const TYPE_STATUS_PENDING	= 'status_pending';
	const TYPE_STATUS_PUBLISHED	= 'status_published';
	const TYPE_STATUS_ARCHIVED	= 'status_archived';

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
	protected $id;

    /**
     * @Column(type="string")
     */
    protected $entityType;

    /**
     * @Column(type="integer")
     */
    protected $entityId;
    
    protected $entityObject;

    /**
     * @Column(type="string")
     */
	protected $type;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\User", inversedBy="logs")
     */
    protected $user;
}