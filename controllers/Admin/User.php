<?php
namespace App\Admin\Controller;

class User extends \Js\App\Controller\Action
{
	public function index()
	{}
	
	public function view()
	{}
	
	public function add()
	{
		return $this->forward('edit');
	}
	
	public function edit()
	{
		$id = $this->request->getParam('id');
		$wrap = $this->entity->wrap($user = isset($id)
			? $this->em->getRepository('\App\User')->find($id)
			: new \App\User());

		if (!$this->request->isPost()) {
			return;
		}

		$wrap->graphFromArray($this->request->getPostParams());
		if (!$wrap->graphIsValid()) {
			return;
		}

		if (!$user->isPersisted()) {
			$this->em->persist($user);
		}
		$this->em->flush();

		return $this->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
	
	public function delete()
	{
		$id = $this->request->getParam('id');
		$user = $this->em->getRepository('\App\User')->find($id);

		$this->em->remove($user);
		$this->em->flush();

		return $this->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}

/*

notes:

methods:
- get
- put
- post
- delete



*/
