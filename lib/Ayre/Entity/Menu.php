<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
    Ayre\Behaviour\Trackable,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class Menu extends Entity implements Trackable
{
    use Trackable\Implementation;
    
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
	protected $id;
	
	/**
     * @ORM\Column(type="string")
     */
    protected $name;

	/**
     * @ORM\ManyToOne(targetEntity="\Ayre\Entity\Tree", inversedBy="menus")
     * @ORM\JoinColumn(nullable=false)
     */
	protected $tree;
}