<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Chalk\Core\Content,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Page extends Content
{
	public static $chalkSingular = 'Page';
	public static $chalkPlural   = 'Pages';
	public static $chalkIsNode   = true;
	public static $chalkIsUrl    = true;

    /**
     * @Column(type="string", nullable=true)
     */
	protected $layout;

    /**
     * @Column(type="text", nullable=true)
     */
	protected $summary;

    /**
     * @Column(type="json_array")
     */
	protected $blocks = [
		['name' => 'primary',   'value' => ''],
		['name' => 'secondary', 'value' => ''],
	];
		
	public function searchableContent()
	{
		return array_merge(parent::searchableContent(), [
			$this->summary,
			implode(' ', \Coast\array_column($this->blocks, 'value')),
		]);
	}
}