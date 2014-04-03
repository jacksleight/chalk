<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
*/
class Document extends Content
{
	protected $type = 'document';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $layout;

    /**
     * @ORM\Column(type="json")
     */
	protected $meta = [];

    /**
     * @ORM\Column(type="json")
     */
	protected $content = [];

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
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
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