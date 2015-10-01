<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Frontend\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

class Alias extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $alias = $this->em('core_alias')
            ->id($req->alias, [
                'isPublished' => isset($this->session->data('__Chalk\Backend')->user)
                    ? null
                    : true,
            ]);
        if (!$alias) {
            return false;
        }
        $req->alias       = $alias;
        $req->view->alias = $alias;
    }

	public function index(Request $req, Response $res)
	{
        return $res->redirect($this->url($req->alias->content));
    }
}