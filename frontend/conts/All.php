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

class All extends Action
{
	public function preDispatch(Request $req, Response $res)
	{
        $name = $req->info->local->name;

		if ($req->path() != $req->node['path']) {
            return $res->redirect(
                $this->url->string($req->node['path']) .
                ($req->queryParams() ? $this->url->query($req->queryParams()) : null)
            );
        }

        $nodes = [$req->node];
        while (isset($nodes[0]['parentId'])) {
            array_unshift($nodes, $this->map[$nodes[0]['parentId']]);
        }
        $req->nodes = $nodes;

        $content = $this->em($req->info->name)
            ->id($req->content);
        $req->content = $content;
        $req->$name   = $content;
        if (!$content) {
            return false;
        }

        $req->isRender = true;
		$req->view     = (object) [
			$name => $req->content,
		];
	}

	public function postDispatch(Request $req, Response $res)
	{
        if (!$req->isRender) {
            return;
        }
		$path = "{$req->controller}/{$req->action}";
		$params = (array) $req->view + [
			'req' => $req,
			'res' => $res,
		];
		$html = $this->view->render($path, $params, $req->info->module->name);
		return $res->html($html);
	}
}