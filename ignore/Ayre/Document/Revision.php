<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Document;

class Revision extends \Ayre\Item\Revision
{
	protected $layout;
	protected $meta		= array('description' => null);
	protected $content	= array();
	
	protected static function _defineMetadata($class)
	{
		return array_merge_recursive(parent::_defineMetadata($class), array(
			'fields' => array(
				'layout' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'meta' => array(
					'type'	=> 'json',
				),
				'content' => array(
					'type'	=> 'json',
				),
			),
		));
	}

	protected function _alterLayoutMetadata($value)
	{
		return array_merge($value, array(
			'values' => \Ayre::$instance->app->getLayouts(),
		));
	}

	public function addMeta($name = null, $value = null)
	{
		if (isset($name) && in_array($name, \JS\array_column($this->meta, 'name'))) {
			return;
		}
		$this->meta[time()] = array(
			'name'	=> $name,
			'value'	=> $value,
		);
		return $this;
	}
	
	public function getSearchContent()
	{
		return array_merge(
			parent::getSearchContent(),
			\JS\array_intersect_key($this->meta, array(
				'description',
				'keywords',
			)),
			$this->content
		);
	}

	public function prePersistUpdate()
	{
		parent::prePersistUpdate();

		foreach ($this->meta as $i => $meta) {
			if (!isset($meta['value'])) {
				unset($this->meta[$i]);
			}
		}
	}
}