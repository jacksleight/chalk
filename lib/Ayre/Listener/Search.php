<?php
/*
* Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
* Any redistribution or reproduction of part or all of the contents in any form is prohibited.
*/

namespace Ayre\Listener;

class Search
{
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

		$entities = array_merge(
			$uow->getScheduledEntityInsertions(),
			$uow->getScheduledEntityUpdates()
		);
		foreach ($entities as $entity) {
			if (!$entity instanceof \Ayre\Item\Revision) {
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