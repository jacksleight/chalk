<?php
namespace App\Admin\Controller;

class Error extends \Js\App\Controller\Action
{
	public function error()
	{
		$this->request->getResponse()->setStatus(500);
	}

	public function notFound()
	{
		$this->request->getResponse()->setStatus(404);
	}
}