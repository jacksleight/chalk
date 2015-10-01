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

class Url extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $url = $this->em('core_url')
            ->id($req->url, [
                'isPublished' => isset($this->session->data('__Chalk\Backend')->user)
                    ? null
                    : true,
            ]);
        if (!$url) {
            return false;
        }
        $req->url       = $url;
        $req->view->url = $url;
    }

	public function index(Request $req, Response $res)
	{
        return $res->redirect($req->url->url);
    }
}