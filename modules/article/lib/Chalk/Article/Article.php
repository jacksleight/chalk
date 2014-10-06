<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Article;

use Chalk\Core\Document,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Article extends Document
{
	public static $info = [
		'singular'	=> 'Article',
		'plural'	=> 'Articles',
	];

	protected $blocks = [
		['name' => 'primary', 'value' => ''],
	];

	public function __construct()
	{
		parent::__construct();
	}
}