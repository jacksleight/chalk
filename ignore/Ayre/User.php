<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

class User extends \Js\Entity\Doctrine
{
	const ROLE_ROOT				= 'root';
	const ROLE_ADMINISTRATOR	= 'administrator';
	const ROLE_EDITOR			= 'editor';
	const ROLE_CONTRIBUTOR		= 'contributor';

	protected $id;
	protected $name;
	protected $emailAddress;
	protected $password;
	protected $passwordPlain;
	protected $loginDate;
	protected $role;
	protected $prefs = array();

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
				'password' => array(
					'type'		=> 'string',
				),
				'passwordPlain' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'persist'	=> false,
					'validator'	=> new \Js\Validator\Chain(array(
						new \Js\Validator\Length(6),
					)),
				),
				'loginDate' => array(
					'type'		=> 'datetime',
					'nullable'	=> true,
				),
				'role' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'values'	=> array(
						self::ROLE_CONTRIBUTOR,
						self::ROLE_EDITOR,
						self::ROLE_ADMINISTRATOR,
						self::ROLE_ROOT,
					),
				),
				'prefs' => array(
					'type'		=> 'json',
				),
			),
		);
	}

	public function setPasswordPlain($passwordPlain)
	{
		if (!isset($passwordPlain)) {
			return;
		}
		$this->passwordPlain = $passwordPlain;
		$hasher = new \Hautelook\Phpass\PasswordHash(8, false);
		$this->password = $hasher->HashPassword($passwordPlain);
	}

	public function isPasswordValid($passwordPlain)
	{
		$hasher = new \Hautelook\Phpass\PasswordHash(8, false);
		return $hasher->CheckPassword($passwordPlain, $this->password);
	}

	protected function _alterPasswordPlainMetadata($value)
	{
		if (!$this->isPersisted()) {
			$value['validator']->addValidator(new \Js\Validator\Set());
		}
		return $value;
	}

	public function getDetail()
	{
		return \Ayre::$instance->locale->message("label_role_" . ($this->role == self::ROLE_ROOT
			? self::ROLE_ADMINISTRATOR
			: $this->role));
	}
}