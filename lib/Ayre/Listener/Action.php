<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Listener;

class Action
{
	protected $_user;

	public function __construct(\Doctrine\Common\EventManager $evm)
	{
		$evm->addEventListener(array(
			\Doctrine\ORM\Events::onFlush,
		), $this);
	}

	public function onFlush(\Doctrine\ORM\Event\OnFlushEventArgs $args)
	{
		$em  = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		$actions	= array();
		$trees		= array();
		foreach ($uow->getScheduledEntityInsertions() as $entity) {
			if ($entity instanceof \Ayre\Tree\Node && !in_array($entity->root->tree, $trees)) {
				$trees[] = $entity->root->tree;
			} else if ($entity instanceof \Ayre\Behaviour\Loggable) {
				$action = $this->_createAction($entity,
					\Ayre\Action::TYPE_CREATE);
				$em->persist($action);
				$actions[] = $action;
			}			
		}
		foreach ($uow->getScheduledEntityUpdates() as $entity) {
			if ($entity instanceof \Ayre\Tree\Node && !in_array($entity->root->tree, $trees)) {
				$trees[] = $entity->root->tree;
			} else if ($entity instanceof \Ayre\Behaviour\Loggable) {
				$changeSet = $uow->getEntityChangeSet($entity);
				if ($entity instanceof \Ayre\Tree && isset($changeSet['root'])) {
					continue;
				}
				if (!isset($changeSet['status']) || count($changeSet) != 1) {
					$action = $this->_createAction($entity,
						\Ayre\Action::TYPE_MODIFY);
					$em->persist($action);
					$actions[] = $action;
				}
				if (isset($changeSet['status'])) {
					$action = $this->_createAction($entity,
						constant('\Ayre\Action::TYPE_STATUS_' . strtoupper($entity->status)));
					$em->persist($action);
					$actions[] = $action;
				}
			}
		}
		foreach ($trees as $tree) {
			$action = $this->_createAction($tree,
				\Ayre\Action::TYPE_MODIFY);
			$em->persist($action);
			$actions[] = $action;
		}

		foreach ($actions as $action) {
			$uow->computeChangeSet(
				$em->getClassMetadata(get_class($action)),
				$action);
		}
	}

	public function _createAction($entity, $type)
	{
		$action	= new \Ayre\Action();
		$action->type = $type;
		if ($entity instanceof \Ayre\Silt) {
			$action->silt = $entity;
		} else if ($entity instanceof \Ayre\Tree) {
			$action->tree = $entity;
		}
		$entity->actions->add($action);
		return $action;
	}
}