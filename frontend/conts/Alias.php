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

class Alias extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $session = $this->session->data('__Chalk');
        $alias = $this->em('core_alias')
            ->id($req->alias, [
                'isPublished' => isset($session->user) ? null : true,
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