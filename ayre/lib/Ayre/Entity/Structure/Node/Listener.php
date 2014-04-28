<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Entity\Structure\Node;

use Ayre\Entity, 
	Doctrine\Common\EventSubscriber,
	Doctrine\ORM\Event\OnFlushEventArgs,
	Doctrine\ORM\Event\PostFlushEventArgs,
	Doctrine\ORM\Events,
	Doctrine\ORM\UnitOfWork;

class Listener implements EventSubscriber
{
	protected $_refresh = false;

	public function getSubscribedEvents()
	{
		return [
			Events::onFlush,
			Events::postFlush,
		];
	}

	public function onFlush(OnFlushEventArgs $args)
	{
		$em	 = $args->getEntityManager();
		$uow = $em->getUnitOfWork();
		
		$entities = array_merge(
			$uow->getScheduledEntityInsertions(),
			$uow->getScheduledEntityUpdates()
		);

		foreach ($entities as $entity) {
			if (
				$entity instanceof Entity\Structure ||
				$entity instanceof Entity\Structure\Node ||
				$entity instanceof Entity\Content
			) {
				$this->_refresh = true;
				break;
			}			
		}
	}

	public function postFlush(PostFlushEventArgs $args)
	{
		if (!$this->_refresh) {
			return;
		}
		$this->_refresh = false;
		
		$em = $args->getEntityManager();

		$structures = $em->getRepository('\Ayre\Entity\Structure')
			->fetchAllForSlugRefresh();
		foreach ($structures as $structure) {
			$nodes = $em->getRepository('\Ayre\Entity\Structure\Node')
				->getChildren($structure->root, false, 'id', 'asc', true);
			foreach ($nodes as $node) {
				$ancestors = array_reverse($node->ancestors);
				if (isset($node->parent)) {
					array_shift($ancestors);
				}
				$node->slug = implode('/', array_map(function($node) {
		            return isset($node->content) ? $node->content->slug : null;
		        }, $ancestors));
			}
		}

		$em->flush();
	}
}