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
     * @Column(type="json")
     */
	protected $meta = [];

    /**
     * @Column(type="json")
     */
	protected $content = [
		'primary' => '',
		'secondary' => '',
	];

	public function addMeta($name = null, $value = null)
	{
		if (isset($name) && in_array($name, \Coast\array_column($this->meta, 'name'))) {
			return;
		}
		$this->meta[time()] = [
			'name'	=> $name,
			'value'	=> $value,
		];
		return $this;
	}
	
	public function searchFields()
	{
		return array_merge(parent::searchFields(), [
			'meta',
			'content',
		]);
	}

	/**
	 * @PrePersist
	 * @PreUpdate
	 */
	public function cleanMeta()
	{
		foreach ($this->meta as $name => $value) {
			if (!isset($value)) {
				unset($this->meta[$name]);
			}
		}
	}
}