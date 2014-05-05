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
class Url extends Content
{
	public static $info = [
		'singular'	=> 'URL',
		'plural'	=> 'URLs',
	];

    /**
     * @ORM\Column(type="url")
     */
	protected $url;
	
	public function searchFields()
	{
		return array_merge(parent::searchFields(), [
			'url',
		]);
	}
}