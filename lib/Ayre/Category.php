<?php
namespace App;

class Category extends \Js\Entity\Doctrine
{
	protected $id;
	protected $name;

	protected $articles;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'category',
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
				'articles' => array(
					'type'		=> 'manyToMany',
					'entity'	=> '\App\Article',
					'inverse'	=> 'categories',
				),
			),	
		);
	}
}
