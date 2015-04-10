<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Coast\Url as CoastUrl,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Url extends Content
{
	public static $_chalkInfo = [
		'singular'	=> 'URL',
		'plural'	=> 'URLs',
	];

    /**
     * @Column(type="coast_url")
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

	public function subtypeLabel()
	{	
		return strtoupper($this->subtype);
	}

	public function clarifier($context = false, $parts = [])
	{
		return parent::clarifier($context, [$this->url->toString()]);
	}
	
	public function searchableContent()
	{
		return array_merge(parent::searchableContent(), [
			$this->url,
		]);
	}
}