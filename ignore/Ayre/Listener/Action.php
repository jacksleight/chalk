<?php
/*
* Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
* Any redistribution or reproduction of part or all of the contents in any form is prohibited.
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

	public function setUser(\Ayre\User $user = null)
	{
		$this->_user = $user;
	}

	public function onFlush(\Doctrine\ORM\Event\OnFlushEventArgs $args)
	{
		$em  = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		$actions		= array();
		$treeRevisions	= array();
		foreach ($uow->getScheduledEntityInsertions() as $entity) {
			if ($entity instanceof \Ayre\Tree\Revision\Node && !in_array($entity->revision, $treeRevisions)) {
				$treeRevisions[] = $entity->revision;
			} else if ($entity instanceof \Ayre\Action\Logged) {
				$action = $this->_createAction($entity,
					\Ayre\Action::TYPE_CREATE);
				$em->persist($action);
				$actions[] = $action;
			}			
		}
		foreach ($uow->getScheduledEntityUpdates() as $entity) {
			if ($entity instanceof \Ayre\Tree\Revision\Node && !in_array($entity->revision, $treeRevisions)) {
				$treeRevisions[] = $entity->revision;
			} else if ($entity instanceof \Ayre\Action\Logged) {
				$changeSet = $uow->getEntityChangeSet($entity);
				if ($entity instanceof \Ayre\Tree\Revision && isset($changeSet['root'])) {
					continue;
				}
				if (!isset($changeSet['status']) || count($changeSet) != 1) {
					$action = $this->_createAction($entity,
						\Ayre\Action::TYPE_UPDATE);
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
		foreach ($treeRevisions as $treeRevision) {
			$action = $this->_createAction($treeRevision,
				\Ayre\Action::TYPE_UPDATE);
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
		$action->user = $this->_user;
		if ($entity instanceof \Ayre\Item\Revision) {
			$action->itemRevision = $entity;
		} else if ($entity instanceof \Ayre\Tree\Revision) {
			$action->treeRevision = $entity;
		}
		$entity->actions->add($action);
		return $action;
	}
}