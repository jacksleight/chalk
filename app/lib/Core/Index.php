<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(options={"engine"="MyISAM"})
*/
class Index extends \Toast\Entity
{
    public static $_chalkInfo = [
        'singular'  => 'Index',
        'plural'    => 'Indexes',
    ];

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
     * @Column(type="text")
     */
	protected $content;

    public function content($content = null)
    {
        if (isset($content)) {
            $this->content = $content;
        }
        return $this->content;
    }
}