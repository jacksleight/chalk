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

class File extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $session = $this->session->data('__Chalk');
        $file = $this->em('core_file')
            ->id($req->file, [
                'isPublished' => isset($session->user) ? null : true,
            ]);
        if (!$file) {
            return false;
        }
        $req->file       = $file;
        $req->view->file = $file;
    }

	public function index(Request $req, Response $res)
	{
        return $res->redirect($this->url->file($req->file->file));
    }
}