<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(options={"engine"="MyISAM"})
*/
class Index extends \Toast\Entity
{
	/**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
	protected $id;

    /**
     * @Column(type="string")
     */
    protected $entity_class;

    /**
     * @Column(type="integer")
     */
    protected $entity_id;
    
    protected $entity_obj;

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