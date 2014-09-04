<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Trackable;

use Ayre,
	Ayre\Behaviour\Trackable,
	Ayre\Core\User,
	Doctrine\Common\EventSubscriber,
	Doctrine\ORM\Event\LifecycleEventArgs,
	Doctrine\ORM\Events,
    Carbon\Carbon;

class Listener implements EventSubscriber
{
	protected $_user;

	public function setUser(User $user)
	{
		$this->_user = $user;
		return $this;
	}

	public function getSubscribedEvents()
	{
		return [
			Events::prePersist,
			Events::preUpdate,
		];
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Trackable) {
			return;
		}

		$entity->createDate = new Carbon();
		$entity->createUser = $this->_user;
		$entity->modifyDate = new Carbon();
		$entity->modifyUser = $this->_user;
	}

	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		
		if (!$entity instanceof Trackable) {
			return;
		}

		$entity->modifyDate = new Carbon();
		$entity->modifyUser = $this->_user;
	}
}