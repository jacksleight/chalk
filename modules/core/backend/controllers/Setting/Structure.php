<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller\Setting;

use Chalk\Chalk,
	Chalk\Controller\Crud,
	Coast\Request,
	Coast\Response;

class Structure extends Crud
{
	protected $_entityClass = 'Chalk\Core\Structure';

	public function preDispatch(Request $req, Response $res)
	{
		parent::preDispatch($req, $res);
		if (!in_array($req->user->role, ['administrator', 'developer'])) {
			return $this->forward('forbidden', 'index');
		}
	}
}