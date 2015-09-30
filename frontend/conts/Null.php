<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Frontend\Controller;

use Chalk\Chalk,
	Coast\Controller\Action,
	Coast\Request,
	Coast\Response;

class Null extends Action
{
	public function index(Request $req, Response $res)
	{
        $req->isRender = false;
    }
}