<?php
namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Chalk\Core\Structure\Node\Iterator,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Setting extends Action
{
	public function index(Request $req, Response $res)
	{
		if ($req->controller == 'setting') {
			return $res->redirect($this->url([
				'controller' => 'user',
			]));
		}
	}
}

