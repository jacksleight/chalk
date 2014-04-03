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
 * @ORM\Table(options={"engine"="MyISAM"})
*/
class Search extends Entity
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
    protected $class;

    /**
     * @ORM\Column(type="integer")
     */
    protected $class_id;
    
    protected $class_obj;

	/**
     * @ORM\Column(type="text")
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