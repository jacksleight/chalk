<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Loggable;

use Ayre,
	Ayre\Entity,
	Ayre\Behaviour\Loggable,
	Ayre\Behaviour\Publishable,
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
			if (!$entity instanceof Entity\Tree\Node) {
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
			$entity = $entity->root->tree;
			if (in_array($entity, $entities, true)) {
				continue;
			}
			$entities[] = $entity;
		}
		foreach ($entities as $i => $entity) {
			if (!$entity instanceof Loggable) {
				continue;
			}
			$changeSet		= $uow->getEntityChangeSet($entity);
			$log			= new Entity\Log();
			$log->class		= Ayre::resolve($entity)->short;
			$log->class_obj	= $entity;
			if (!isset($entity->id)) {
				$log->type = Entity\Log::TYPE_CREATE;
			} else if (
				$entity instanceof Publishable &&
				isset($changeSet['status']) &&
				$changeSet['status'][0] != $changeSet['status'][1]
			) {
				$log->type = constant('Entity\Log::TYPE_STATUS_' . strtoupper($entity->status));
			} else {
				$log->type = Entity\Log::TYPE_MODIFY;
			}
			$this->_updates[] = $log;
		}

		$entities = array_merge(
			$uow->getScheduledEntityDeletions()
		);
		foreach ($entities as $i => $entity) {
			if (!$entity instanceof Loggable) {
				continue;
			}
			$logs = $em->getRepository('Ayre\Entity\Log')->fetchAll($entity);
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
			$entity->class_id = $entity->class_obj->id;
			$em->persist($entity);
		}

		while (count($this->_deletions)) {
			$entity = array_shift($this->_deletions);
			$em->remove($entity);
		}	

		$em->flush();
	}
}