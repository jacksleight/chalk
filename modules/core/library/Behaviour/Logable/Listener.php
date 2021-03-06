<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Logable;

use Chalk\Chalk,
	Chalk\Core\Structure\Node,
	Chalk\Core\Log,
	Chalk\Core\Behaviour\Logable,
	Chalk\Core\Behaviour\Publishable,
	Doctrine\Common\EventSubscriber,
	Doctrine\ORM\Event\OnFlushEventArgs,
	Doctrine\ORM\Event\PostFlushEventArgs,
	Doctrine\ORM\Events,
	Doctrine\ORM\UnitOfWork;

class Listener implements EventSubscriber
{
	protected $_updates		= [];
	protected $_deletions	= [];

	public function getSubscribedEvents()
	{
		return [
			Events::onFlush,
			Events::postFlush,
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
			if (!$entity instanceof Node) {
				continue;
			}
			$changeSet = $uow->getEntityChangeSet($entity);
			if (
				isset($changeSet['slug']) &&
				!isset($changeSet['slug'][0]) &&
				isset($changeSet['slug'][1])
			) {
				continue;
			}
			$entity = $entity->structure;
			if (in_array($entity, $entities, true)) {
				continue;
			}
			$entities[] = $entity;
		}
		foreach ($entities as $i => $entity) {
			if (!$entity instanceof Logable) {
				continue;
			}
			$changeSet			= $uow->getEntityChangeSet($entity);
			$log				= new Log();
			$log->entity		= Chalk::info($entity)->name;
			$log->entityObject	= $entity;
			if (!isset($entity->id)) {
				$log->type = Log::TYPE_CREATE;
			} else if (
				$entity instanceof Publishable &&
				isset($changeSet['status']) &&
				$changeSet['status'][0] != $changeSet['status'][1]
			) {
				$log->type = constant('Chalk\Core\Log::TYPE_STATUS_' . strtoupper($entity->status));
			} else {
				$log->type = Log::TYPE_MODIFY;
			}
			$this->_updates[] = $log;
		}

		$entities = array_merge(
			$uow->getScheduledEntityDeletions()
		);
		foreach ($entities as $i => $entity) {
			if (!$entity instanceof Logable) {
				continue;
			}
			$logs = $em->getRepository('Chalk\Core\Log')->all(['entity' => $entity]);
			$this->_deletions = array_merge($this->_deletions, $logs);
		}
	}

	public function postFlush(PostFlushEventArgs $args)
	{
		if (!count($this->_updates) && !count($this->_deletions)) {
			return;
		}

		$em = $args->getEntityManager();

		while (count($this->_updates)) {
			$entity = array_shift($this->_updates);
			$entity->entityId = $entity->entityObject->id;
			$em->persist($entity);
		}

		while (count($this->_deletions)) {
			$entity = array_shift($this->_deletions);
			$em->remove($entity);
		}	

		$em->flush();
	}
}