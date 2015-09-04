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
        $session = $this->session->data('__Chalk');

        if ($req->path() != $req->node['path']) {
            return $res->redirect(
                $this->url->string($req->node['path']) .
                ($req->queryParams() ? $this->url->query($req->queryParams()) : null)
            );
        }

        $nodes = [$req->node];
        while (isset($nodes[0]['parentId'])) {
            array_unshift($nodes, $this->nodeMap[$nodes[0]['parentId']]);
        }
        $req->nodes = $nodes;

        $content = $this->em($req->info->name)
            ->id($req->content, isset($session->user) ? ['isPublished' => null] : []);
        if (!$content) {
            return false;
        }

        $name = $req->info->local->name;

        $req->content = $content;
        $req->$name   = $content;

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
		
        $config = $this->chalk->config->viewScripts;
        
        $path = "{$config[0]}/{$req->info->module->name}/{$req->controller}/{$req->action}";
		$params = (array) $req->view + [
			'req' => $req,
			'res' => $res,
		];

		$html = $this->view->render($path, $params, $config[1]);
        $html = $this->app->parse($html);
		return $res->html($html);
	}
}