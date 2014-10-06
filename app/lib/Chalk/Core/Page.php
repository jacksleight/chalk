<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @HasLifecycleCallbacks
*/
class Page extends Document
{
	public static $info = [
		'singular'	=> 'Page',
		'plural'	=> 'Pages',
	];

	protected $blocks = [
		['name' => 'primary',   'value' => ''],
		['name' => 'secondary', 'value' => ''],
	];

    /**
     * @Column(type="string", nullable=true)
     */
	protected $layout;
}