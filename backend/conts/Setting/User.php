<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Backend\Controller\Setting;

use Chalk\Chalk,
	Chalk\Controller\Basic,
	Coast\Request,
	Coast\Response;

class User extends Basic
{
	protected $_entityClass = 'Chalk\Core\User';

	public function preDispatch(Request $req, Response $res)
	{
		parent::preDispatch($req, $res);
		if (!in_array($req->user->role, ['administrator', 'developer'])) {
			return $this->forward('forbidden', 'index');
		}
	}
}