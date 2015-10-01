<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Frontend\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

class Page extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $page = $this->em('core_page')
            ->id($req->page, [
                'isPublished' => isset($this->session->data('__Chalk\Backend')->user)
                    ? null
                    : true,
            ]);
        if (!$page) {
            return false;
        }
        $req->page       = $page;
        $req->view->page = $page;
    }

	public function index(Request $req, Response $res)
	{}
}