<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\File;

use Chalk\Chalk,
	Chalk\Core\File,
	Doctrine\Common\EventSubscriber,
	Doctrine\ORM\Event\OnFlushEventArgs,
	Doctrine\ORM\Event\LifecycleEventArgs,
	Doctrine\ORM\Events,
	Doctrine\ORM\UnitOfWork;

class Listener implements EventSubscriber
{
	public function getSubscribedEvents()
	{
		return [
			Events::onFlush,
			Events::postLoad,
		];
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em  = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		$entities = array_merge(
			$uow->getScheduledEntityInsertions(),
			$uow->getScheduledEntityUpdates()
		);
		foreach ($entities as $entity) {
			if (!$entity instanceof File || !isset($entity->newFile)) {
				continue;
			}
			$entity->move($entity->newFile);
			$uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($entity)), $entity);
		}

		$entities = $uow->getScheduledEntityDeletions();
		foreach ($entities as $entity) {
			if (!$entity instanceof File) {
				continue;
			}
			$entity->remove();
		}
	}

	public function postLoad(LifecycleEventArgs $args)
	{
		$em		= $args->getEntityManager();
		$entity	= $args->getEntity();

		if (!$entity instanceof File) {
			return;
		}
		$entity->init();
	}
}