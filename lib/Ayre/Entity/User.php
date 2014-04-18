<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity;

use Ayre\Entity,
    Ayre\Behaviour\Trackable,
    Ayre\Behaviour\Searchable,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class User extends Entity implements Trackable, Searchable
{
    use Trackable\Implementation;

	const ROLE_ROOT				= 'root';
	const ROLE_ADMINISTRATOR	= 'administrator';
	const ROLE_EDITOR			= 'editor';
	const ROLE_CONTRIBUTOR		= 'contributor';

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
	protected $id;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $name;
	
	/**
     * @ORM\Column(type="boolean")
     */
	protected $isEnabled = true;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $emailAddress;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $password;
	
	protected $passwordPlain;

	protected $passwordPlainConfirm;
	
	/**
     * @ORM\Column(type="datetime", nullable=true)
     */
	protected $loginDate;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $role = 'contributor';
	
	/**
     * @ORM\Column(type="json")
     */
	protected $prefs = [];

	/**
     * @ORM\OneToMany(targetEntity="\Ayre\Entity\Log", mappedBy="user")
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
					'validator'	=> new \JS\Validator\Chain(array(
						new \JS\Validator\Length(6),
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
			$data['validator']->addValidator(new \JS\Validator\Set());
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