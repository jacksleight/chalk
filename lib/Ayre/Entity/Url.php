<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
	Coast\Url as CoastUrl,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Url extends Content
{
	public static $info = [
		'singular'	=> 'URL',
		'plural'	=> 'URLs',
	];

    /**
     * @Column(type="url")
     */
	protected $url;

	public function url(CoastUrl $url = null)
	{
		if (isset($url)) {
			$this->url		= $url;
			$this->subtype	= $url->scheme();
			return $this;
		}
		return $this->url;
	}
	
	public function searchFields()
	{
		return array_merge(parent::searchFields(), [
			'url',
		]);
	}
}