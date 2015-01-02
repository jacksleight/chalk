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
 * @HasLifecycleCallbacks
*/
class Page extends Content
{
	public static $_chalkInfo = [
		'singular'	=> 'Page',
		'plural'	=> 'Pages',
	];

    /**
     * @Column(type="string", nullable=true)
     */
	protected $layout;

    /**
     * @Column(type="text", nullable=true)
     */
	protected $summary;

    /**
     * @Column(type="coast_array")
     */
	protected $blocks = [
		['name' => 'primary',   'value' => ''],
		['name' => 'secondary', 'value' => ''],
	];
		
	public function searchContent()
	{
		return array_merge(parent::searchContent(), [
			strip_tags($this->summary),
		], array_map(function($block) {
			return strip_tags($block['value']);
		}, $this->blocks));
	}
}