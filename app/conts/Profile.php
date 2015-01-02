<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Profile extends \Chalk\Core\Controller\User
{
	public function edit(Request $req, Response $res)
	{
		$req->id = $req->user->id;
		return parent::edit($req, $res);
	}
}