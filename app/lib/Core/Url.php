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
	public static $chalkSingular = 'External Link';
	public static $chalkPlural   = 'External Links';
	public static $chalkIsNode   = true;
	public static $chalkIsUrl    = true;

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

	public static function staticSubtypeLabel($subtype)
	{	
		return strtoupper($subtype);
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