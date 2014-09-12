<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre\Core,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Document extends Content
{
	public static $info = [
		'singular'	=> 'Document',
		'plural'	=> 'Documents',
	];

    /**
     * @Column(type="text", nullable=true)
     */
	protected $summary;

    /**
     * @Column(type="coast_json")
     */
	protected $blocks = [];
	
	public function searchFields()
	{
		return array_merge(parent::searchFields(), [
			'summary',
			'blocks',
		]);
	}
}