<?php
namespace Chalk\Core\Controller;

use Chalk\Chalk,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Profile extends \Chalk\Core\Controller\User
{
	public function edit(Request $req, Response $res)
	{
		$req->id = $req->user->id;
		return parent::edit($req, $res);
	}
}