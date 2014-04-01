<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Ayre,
    Coast\Model,
	Doctrine\Common\Collections\ArrayCollection,
    Doctrine\ORM\Mapping as ORM,
    Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
*/
class User extends Model
{
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
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="create")
     */
    protected $createUser;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $modifyDate;

    /**
     * @ORM\ManyToOne(targetEntity="Ayre\User")
     * @Gedmo\Blameable(on="update")
     */
    protected $modifyUser;

	public function setPasswordPlain($passwordPlain)
	{
		if (!isset($passwordPlain)) {
			return;
		}
		$this->passwordPlain = $passwordPlain;
		$this->password = password_hash($passwordPlain, PASSWORD_DEFAULT);
	}

	public function isPasswordValid($passwordPlain)
	{
		return password_verify($passwordPlain, $this->password);
	}
}