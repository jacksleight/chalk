<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

class Menu extends \Js\Entity\Doctrine
{
	protected $id;
	protected $name;

	protected $tree;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'menu',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'name' => array(
					'type'		=> 'string',
				),
			),
			'associations' => array(
				'tree' => array(
					'type'		=> 'manyToOne',
					'entity'	=> 'Ayre\Tree',
					'inverse'	=> 'menus',
				),
			),
		);
	}

	public function getDetail()
	{
		return $this->tree->name;
	}
}