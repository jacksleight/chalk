<?php
namespace Ayre\Core\Controller;

use Coast\App\Controller\Action,
	Coast\Request,
	Coast\Response;

class Auth extends Action
{
	public function login(Request $req, Response $res)
	{
		$req->view->login = $wrap = $this->em->wrap(
			$login = new \Ayre\Login()
		);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->isValid()) {
			return;
		}

		$user = $this->em('core_user')->fetchByEmailAddress($login->emailAddress);
		if (!isset($user)) {
			$login->password = null;
			$login->addError('emailAddress', 'validator_login_invalid');
			return;
		} else if (!$user->verifyPassword($login->password)) {
			$login->password = null;
			$login->addError('emailAddress', 'validator_login_invalid');
			return;
		}

		$user->loginDate = new \Carbon\Carbon();
		$this->em->flush();

		$session =& $req->session('ayre');
		$session->user = $user->id;

		return $res->redirect($this->url(array(), 'index', true));
	}

	public function logout(Request $req, Response $res)
	{
		$session =& $req->session('ayre');
		$session->user = null;

		return $res->redirect($this->url(array(), 'login', true));
	}
}