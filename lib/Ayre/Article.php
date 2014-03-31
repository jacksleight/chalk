<?php
namespace App;

class Article extends \Js\Entity\Doctrine
{
	protected $id;
	protected $title;

	protected $user;
	protected $categories;
	protected $content;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'article',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'title' => array(
					'type'		=> 'string',
				),
			),
			'associations' => array(
				'user' => array(
					'type'		=> 'manyToOne',
					'entity'	=> '\App\User',
					'inverse'	=> 'articles',
				),
				'categories' => array(
					'type'		=> 'manyToMany',
					'entity'	=> '\App\Category',
					'inverse'	=> 'articles',
					'owner'		=> true,
					'table'		=> array(
						'name' => 'demo_category_article',
						'join' => array(
							'name'		=> 'article',
							'onDelete'	=> 'CASCADE',
						),
						'inverseJoin' => array(
							'name'		=> 'category',
							'onDelete'	=> 'CASCADE',
						),
					),
				),
				'content' => array(
					'type'		=> 'oneToOne',
					'entity'	=> '\App\Content',
					'inverse'	=> 'article',
				),
			),	
		);
	}
}
