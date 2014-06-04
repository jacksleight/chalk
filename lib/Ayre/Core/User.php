<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Core;

use Ayre\Core,
    Ayre\Behaviour\Trackable,
    Ayre\Behaviour\Searchable,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class User extends \Toast\Entity implements Trackable, Searchable
{
	public static $info = [
		'singular'	=> 'User',
		'plural'	=> 'Users',
	];
	
    use Trackable\Implementation;

	const ROLE_ROOT				= 'root';
	const ROLE_ADMINISTRATOR	= 'administrator';
	const ROLE_EDITOR			= 'editor';
	const ROLE_CONTRIBUTOR		= 'contributor';

	/**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
	protected $id;
	
	/**
     * @Column(type="string")
     */
	protected $name;
	
	/**
     * @Column(type="boolean")
     */
	protected $isEnabled = true;
	
	/**
     * @Column(type="string")
     */
	protected $emailAddress;
	
	/**
     * @Column(type="string")
     */
	protected $password;
	
	protected $passwordPlain;

	protected $passwordPlainConfirm;
	
	/**
     * @Column(type="datetime", nullable=true)
     */
	protected $loginDate;
	
	/**
     * @Column(type="string")
     */
	protected $role = 'contributor';
	
	/**
     * @Column(type="coast_json")
     */
	protected $prefs = [];

	/**
     * @OneToMany(targetEntity="\Ayre\Core\Log", mappedBy="user")
     */
	protected $logs;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'role' => array(
					'values' => [
						self::ROLE_CONTRIBUTOR		=> 'Contributor',
						self::ROLE_EDITOR			=> 'Editor',
						self::ROLE_ADMINISTRATOR	=> 'Administrator',
					],
				),
				'passwordPlain' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'validator'	=> new \Toast\Validator\Chain(array(
						new \Toast\Validator\Length(6),
					)),
				),
				'passwordPlainConfirm' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
			),
		);
	}

	protected function _alterMetadata($name, $data)
	{
		if (!isset($this->password) && in_array($name, ['passwordPlain', 'passwordPlainConfirm'])) {
			$data['validator']->addValidator(new \Toast\Validator\Set());
		}
		return $data;
	}

	public function passwordPlain($passwordPlain = null)
	{
		if (!isset($passwordPlain)) {
			return $this->passwordPlain;
		}
		$this->passwordPlain = $passwordPlain;
		$this->password = password_hash($passwordPlain, PASSWORD_DEFAULT);
	}

	public function verifyPassword($passwordPlain)
	{
		return password_verify($passwordPlain, $this->password);
	}

	public function pref($name, $value = null)
	{
		if (isset($value)) {
			$this->prefs[$name] = $value;
			return $this;
		}
		return isset($this->prefs[$name])
			? $this->prefs[$name]
			: null;
	}
	
	public function searchFields()
	{
		return [
			'name',
			'emailAddress',
		];
	}
	
	public function __toString()
	{
		return $this->name;
	}
}