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
class Menu extends Model
{
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
     * @ORM\ManyToOne(targetEntity="Ayre\Tree", inversedBy="menus")
     */
	protected $tree;

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
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $modifyDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="update")
     */
    protected $modifyUser;
}