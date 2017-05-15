<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk,
	Chalk\Controller\Crud,
	Coast\Request,
	Coast\Response;

class Profile extends Crud
{
	protected $_entityClass = 'Chalk\Core\User';

	public function edit(Request $req, Response $res)
	{
		$req->id = $req->user->id;
		return parent::edit($req, $res);
	}

	public function delete(Request $req, Response $res)
	{
		throw new \Exception();
	}
}