<?php
namespace App;

class User extends \Js\Entity\Doctrine
{
	protected $id;
	protected $name;
	protected $emailAddress;

	protected $articles;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'user',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'name' => array(
					'type'		=> 'string',
				),
				'emailAddress' => array(
					'type'		=> 'string',
					'validator'	=> new \Js\Validator\Chain(array(
						new \Js\Validator\EmailAddress(),
					)),
				),
			),
			'associations' => array(
				'articles' => array(
					'type'		=> 'oneToMany',
					'entity'	=> '\App\Article',
					'inverse'	=> 'user',
				),
			),	
		);
	}
}
