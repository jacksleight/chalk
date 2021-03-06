<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Trackable;

use Chalk\Chalk,
	Chalk\Core\Behaviour\Trackable,
	Chalk\Core\User,
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
		$entity->updateDate = new Carbon();
		$entity->updateUser = $this->_user;
	}

	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		
		if (!$entity instanceof Trackable) {
			return;
		}

		$entity->updateDate = new Carbon();
		$entity->updateUser = $this->_user;
	}
}