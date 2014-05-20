<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @HasLifecycleCallbacks
*/
class Document extends Content
{
    /**
     * @Column(type="coast_json")
     */
	protected $metas = [];

    /**
     * @Column(type="coast_json")
     */
	protected $contents = [
		'primary'	=> '',
		'secondary'	=> '',
	];

	public function metas(array $metas = null)
	{
		if (isset($metas)) {
			foreach ($metas as $i => $meta) {
				if (!strlen($meta['value'])) {
					unset($metas[$i]);
				}
			}
			$this->metas = $metas;
		}
		return $this->metas;
	}
	
	public function searchFields()
	{
		return array_merge(parent::searchFields(), [
			'contents',
		]);
	}
}