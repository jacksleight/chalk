<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Listener;

class Search
{
	public function onFlush(\Doctrine\ORM\Event\OnFlushEventArgs $args)
	{
		$em  = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		$entities = array_merge(
			$uow->getScheduledEntityInsertions(),
			$uow->getScheduledEntityUpdates()
		);
		foreach ($entities as $entity) {
			if (!$entity instanceof \Ayre\Silt) {
				continue;
			}
			$search = $entity->search;
			$search->content = implode(' ', $entity->searchContent);
			$uow->recomputeSingleEntityChangeSet(
				$em->getClassMetadata(get_class($search)),
				$search);
		}
	}
}