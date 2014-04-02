<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Listener;

use Ayre,
	Ayre\Behaviour,
	Doctrine\ORM\UnitOfWork;

class Action
{
	public function onFlush(\Doctrine\ORM\Event\OnFlushEventArgs $args)
	{
		$em  = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		$entities = array_merge(
			$uow->getScheduledEntityInsertions(),
			$uow->getScheduledEntityUpdates()
		);

		$loggables = [];
		foreach ($entities as $entity) {
			if ($entity instanceof Ayre\Tree\Node) {
				$changeSet = $uow->getEntityChangeSet($entity);
				if (isset($changeSet['slug'])
					&& !isset($changeSet['slug'][0])
					&& isset($changeSet['slug'][1])) {
					continue;
				}
				$entity = $entity->root->tree;
			}
			if (!$entity instanceof Behaviour\Loggable || in_array($entity, $loggables, true)) {
				continue;
			}
			$loggables[] = $entity;
		}

		$actions = [];
		foreach ($loggables as $entity) {
			if (!isset($entity->id)) {
				$action = $this->_createAction($entity,
					Ayre\Action::TYPE_CREATE);
				$em->persist($action);
				$actions[] = $action;
			} else {
				$changeSet = $uow->getEntityChangeSet($entity);
				if ($entity instanceof Behaviour\Publishable
					&& isset($changeSet['status'])
					&& $changeSet['status'][0] != $changeSet['status'][1]) {
					$action = $this->_createAction($entity,
						constant('Ayre\Action::TYPE_STATUS_' . strtoupper($entity->status)));
					$em->persist($action);
					$actions[] = $action;
				} else {
					$action = $this->_createAction($entity,
						Ayre\Action::TYPE_MODIFY);
					$em->persist($action);
					$actions[] = $action;
				}
			}
		}

		foreach ($actions as $action) {
			$uow->computeChangeSet($em->getClassMetadata('Ayre\Action'), $action);
		}
	}

	public function _createAction($entity, $type)
	{
		$action	= new Ayre\Action();
		$action->type = $type;
		if ($entity instanceof Ayre\Silt) {
			$action->silt = $entity;
		} else if ($entity instanceof Ayre\Tree) {
			$action->tree = $entity;
		}
		$entity->actions->add($action);
		return $action;
	}
}