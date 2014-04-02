<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Ayre\Behaviour,
    Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class User extends Model implements Behaviour\Trackable
{
    use Behaviour\Trackable\Implementation;

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
     * @ORM\Column(type="string")
     */
	protected $emailAddress;
	
	/**
     * @ORM\Column(type="string")
     */
	protected $password;
	
	protected $passwordPlain;
	
	/**
     * @ORM\Column(type="datetime", nullable=true)
     */
	protected $loginDate;
	
	/**
     * @ORM\Column(type="string", nullable=true)
     */
	protected $role;
	
	/**
     * @ORM\Column(type="json")
     */
	protected $prefs = array();

	/**
     * @ORM\OneToMany(targetEntity="Ayre\Action", mappedBy="user")
     */
	protected $actions;

	public function passwordPlain($passwordPlain)
	{
		if (!isset($passwordPlain)) {
			return;
		}
		$this->passwordPlain = $passwordPlain;
		$this->password = password_hash($passwordPlain, PASSWORD_DEFAULT);
	}

	public function verifyPassword($passwordPlain)
	{
		return password_verify($passwordPlain, $this->password);
	}
}