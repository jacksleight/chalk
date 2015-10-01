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

class All extends Action
{
	public function preDispatch(Request $req, Response $res)
	{
        $req->view = (object) [];

        if (isset($req->node)) {
            // if ($req->path() != $req->node['path']) {
            //     return $res->redirect(
            //         $this->url->string($req->node['path']) .
            //         ($req->queryParams() ? $this->url->query($req->queryParams(), true) : null)
            //     );
            // }
            $nodes = [$req->node];
            while (isset($nodes[0]['parentId'])) {
                array_unshift($nodes, $this->nodeMap[$nodes[0]['parentId']]);
            }
            $req->nodes = $nodes;
        }
	}

	public function postDispatch(Request $req, Response $res)
	{	
        $config = $this->chalk->config->viewScripts;
        
        $path = "{$config[0]}/{$req->module}/{$req->controller}/{$req->action}";
		$params = (array) $req->view + [
			'req' => $req,
			'res' => $res,
		];

		$html = $this->view->render($path, $params, $config[1]);
        $html = $this->app->parse($html);
		return $res->html($html);
	}
}