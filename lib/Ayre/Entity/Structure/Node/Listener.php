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
			if ($entity instanceof Entity\Structure\Node || ($entity instanceof Entity\Content && $entity->isCurrent())) {
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

		$structs = $em->getRepository('\Ayre\Entity\Structure')
			->fetchAllForSlugRefresh();
		foreach ($structs as $struct) {
			$paths = [];
			$nodes = $em->getRepository('\Ayre\Entity\Structure')
				->fetchNodes($struct);
			foreach ($nodes as $node) {

				var_dump($node->id);

				$path = $node->isRoot()
					? [$node]
					: $node->parents(true);
				$path = implode('/', array_map(function($node) {
		            return $node->slugSmart;
		        }, $path));
				$i = 0;
				do {
					$temp = $path;
					if ($i > 0) {
						$temp .= "-{$i}";
					}
					$i++;
				} while (in_array($temp, $paths));
				$node->path	= $temp;
				$paths[]	= $node->path;
			}
		}
		$em->flush();
	}
}
