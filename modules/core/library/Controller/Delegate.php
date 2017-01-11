<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

abstract class Delegate extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $content = $this->em('core_content')
            ->id($req->content, [
                'isPublished' => isset($this->session->data('__Chalk\Backend')->user)
                    ? null
                    : true,
            ]);
        if (!$content) {
            return false;
        }
        $req->content       = $content;
        $req->view->content = $content;
    }
}