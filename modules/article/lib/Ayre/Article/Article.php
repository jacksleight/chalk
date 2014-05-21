<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Article;

use Ayre\Core\Document,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Article extends Document
{
	protected $contents = [
		'primary'	=> '',
	];

    /**
     * @Column(type="date")
     */
	protected $activeDate;

	public function __construct()
	{
		parent::__construct();

		$this->activeDate = new \Carbon\Carbon();
	}
}