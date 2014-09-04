<?php
namespace Ayre\Core\Controller;

use Ayre,
	Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Profile extends Ayre\Core\Controller\User
{
	public function edit(Request $req, Response $res)
	{
		$req->id = $req->user->id;
		return parent::edit($req, $res);
	}
}