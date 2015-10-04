<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Frontend\Controller;

use Chalk\App as Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

class Null extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $null = $this->em('core_content')
            ->id($req->null, [
                'isPublished' => isset($this->session->data('__Chalk\Backend')->user)
                    ? null
                    : true,
            ]);
        if (!$null) {
            return false;
        }
        $req->null       = $null;
        $req->view->null = $null;
    }

	public function index(Request $req, Response $res)
	{
        return $this->stop();
    }
}