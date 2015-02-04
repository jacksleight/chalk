<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Chalk\Core\Structure\Node\Iterator,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Setting extends Action
{
	public function index(Request $req, Response $res)
	{
		return $res->redirect($this->url([
			'controller' => 'setting_user',
		]));
	}
}

