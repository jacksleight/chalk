<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

class Search extends \Js\Entity\Doctrine
{
	protected $revision;

	protected $id;
	protected $content;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'search',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'content' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
			),
			'associations' => array(
				'revision' => array(
					'type'		=> 'oneToOne',
					'entity'	=> "Ayre\Item\Revision",
					'inverse'	=> 'search',
					'owner'		=> true,
				),
			),
		);
	}
}