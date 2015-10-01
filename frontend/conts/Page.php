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

class Page extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $session = $this->session->data('__Chalk');
        $page = $this->em('core_page')
            ->id($req->page, [
                'isPublished' => isset($session->user) ? null : true,
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