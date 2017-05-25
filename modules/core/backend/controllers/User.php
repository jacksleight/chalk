<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk,
	Chalk\Core\Backend\Controller\Entity,
	Coast\Request,
	Coast\Response;

class User extends Entity
{
	protected $_entityClass = 'Chalk\Core\User';

	public function preDispatch(Request $req, Response $res)
	{
		parent::preDispatch($req, $res);
		if (!in_array($this->user->role, ['administrator', 'developer'])) {
			return $this->forward('forbidden', 'index');
		}
	}
}