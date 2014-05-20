<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre\Core,
    Carbon\Carbon,
    Ayre\Behaviour\Trackable,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Log extends \Toast\Entity implements Trackable
{
    use Trackable\Implementation;

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
}