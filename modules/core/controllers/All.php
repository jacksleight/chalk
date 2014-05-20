<?php
namespace Ayre\Controller;

use Coast\App\Controller\Action,
	Coast\App\Request,
	Coast\App\Response;

class All extends Action
{
	public function preDispatch(Request $req, Response $res)
	{
		$session =& $req->session('ayre');
		if (!isset($session->user) && $req->controller !== 'auth') {
			return $res->redirect($this->url(array(), 'login', true));
		}

		$req->view = (object) [];

		if ($req->controller == 'auth') {
			return;
		}

		$req->user = $this->em('Ayre\Core\User')->fetch($session->user);
		if (!isset($req->user)) {
			$session->user = null;
			return $res->redirect($this->url(array(), 'login', true));
		}

		$this->em->trackable()->setUser($req->user);

		if ($req->controller != 'index' || $req->action != 'prefs') {
			$name	= "query_" . md5(serialize($req->route['params']));
			$value	= $req->queryParams();
			if (count($value)) {
				$req->user->pref($name, $value);
				$this->em->flush();
			} else {
				$value = $req->user->pref($name);
				if (isset($value)) {
					$req->queryParams($value);
				}
			}
		}
	}

	public function postDispatch(Request $req, Response $res)
	{
		$controller	= strtolower(str_replace('_', '/', $req->controller));
		$action		= strtolower(str_replace('_', '-', $req->action));
		$path = isset($req->view->path)
			? $req->view->path
			: "{$controller}/{$action}";
		return $res->html($this->view->render($path, [
			'req' => $req,
			'res' => $res,
		] + (array) $req->view));
	}
}