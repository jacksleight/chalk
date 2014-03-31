<?php
namespace App;

class Content extends \Js\Entity\Doctrine
{
	protected $id;
	protected $description;

	protected $article;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'content',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'type'		=> 'integer',
				),
			),
			'associations' => array(
				'article' => array(
					'type'		=> 'oneToOne',
					'entity'	=> '\App\Article',
					'inverse'	=> 'content',
					'owner'		=> true,
					'onDelete'	=> 'CASCADE'
				),
			),	
		);
	}
}
