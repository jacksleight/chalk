<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core;
use Chalk\Entity;
use Chalk\Core\Behaviour\Trackable;
use Chalk\Core\Behaviour\Searchable;
use Doctrine\Common\Collections\ArrayCollection;
use Coast\Validator;

/**
 * @Entity
 * @Table(
 *     uniqueConstraints={@UniqueConstraint(columns={"emailAddress"})}
 * )
*/
class User extends Entity implements Trackable, Searchable
{
	public static $chalkSingular = 'User';
	public static $chalkPlural   = 'Users';
	public static $chalkIcon     = 'user';
	
    use Trackable\Entity;
    use Searchable\Entity;

	const ROLE_DEVELOPER		= 'developer';
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
     * @Column(type="json_array")
     */
	protected $prefs = [];

	/**
     * @OneToMany(targetEntity="\Chalk\Core\Log", mappedBy="user")
     */
	protected $logs;

	protected static function _defineMetadata($class)
	{
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
				'role' => array(
					'values' => [
						self::ROLE_CONTRIBUTOR		=> 'Contributor',
						self::ROLE_EDITOR			=> 'Editor',
						self::ROLE_ADMINISTRATOR	=> 'Administrator',
						self::ROLE_DEVELOPER		=> 'Developer',
					],
				),
				'emailAddress' => array(
					'validator'	=> (new Validator)
						->emailAddress(),
				),
				'passwordPlain' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'validator'	=> (new Validator)
						->length(6),
				),
			),
		));
	}

	protected function _alterMetadata($name, $value)
	{
		if ($name == 'passwordPlain' && $this->isNew()) {
			$validator = (new Validator)
				->set();
			$value['nullable'] = false;
			$value['validator']->steps($validator->steps(), 0);
		}
		return $value;
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
	
    public function searchContent(array $content = [])
    {
        return [
            $this->name,
            $this->emailAddress,
        ];
    }
		
	public function isDeveloper()
	{
		return $this->role == self::ROLE_DEVELOPER;
	}
	
	public function isAdministrator()
	{
		return $this->role == self::ROLE_ADMINISTRATOR || $this->isDeveloper();
	}

	public function previewText($context = false)
	{
        $parts = parent::previewText($context);
		$parts[] = $this->emailAddress;
		return $parts;
	}
}