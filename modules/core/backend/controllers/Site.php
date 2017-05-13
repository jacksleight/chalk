<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\App as Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

class Site extends Action
{
	public function index(Request $req, Response $res)
	{
		$items = $this->navList->children('core_site');
		if (count($items)) {
			$item = current($items);
			return $res->redirect($item['url']);
		}
	}
}