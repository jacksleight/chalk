<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\App as Chalk,
	Chalk\Core\Structure\Node\Iterator,
	Coast\Controller\Action,
	Coast\Request,
	Coast\Response;

class Setting extends Action
{
	public function index(Request $req, Response $res)
	{
		$items = $this->navList->children('core_setting');
		if (count($items)) {
			$item = current($items);
			return $res->redirect($this->url($item['url'][0], $item['url'][1], true));
		}
	}
}