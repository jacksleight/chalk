<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
    Chalk\Behaviour\Trackable,
    Chalk\Behaviour\Searchable,
	Doctrine\Common\Collections\ArrayCollection;
use Respect\Validation\Validator;

/**
 * @Entity
*/
class User extends \Toast\Entity implements Trackable, Searchable
{
	public static $_chalkInfo = [
		'singular'	=> 'User',
		'plural'	=> 'Users',
	];
	
    use Trackable\Entity;

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

	/**
     * @Column(type="string", nullable=true)
     */
	protected $token;
	
	/**
     * @Column(type="datetime", nullable=true)
     */
	protected $tokenDate;
	
	/**
     * @Column(type="datetime", nullable=true)
     */
	protected $loginDate;
	
	/**
     * @Column(type="string")
     */
	protected $role = 'contributor';
	
	/**
     * @Column(type="coast_array")
     */
	protected $prefs = [];

	/**
     * @OneToMany(targetEntity="\Chalk\Core\Log", mappedBy="user")
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
					'validator'	=> Validator::length(6),
				),
			),
		);
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
		if (func_num_args() > 1) {
			$this->prefs[$name] = $value;
			return $this;
		}
		return isset($this->prefs[$name])
			? $this->prefs[$name]
			: null;
	}
	
	public function searchContent()
	{
		return [
			$this->name,
			$this->emailAddress,
		];
	}
		
	public function isRoot()
	{
		return $this->role == self::ROLE_ROOT;
	}
	
	public function isAdministrator()
	{
		return $this->role == self::ROLE_ADMINISTRATOR || $this->role == self::ROLE_ROOT;
	}
}