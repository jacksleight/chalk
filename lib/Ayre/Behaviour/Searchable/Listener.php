<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Searchable;

use Ayre,
	Ayre\Behaviour\Searchable,
	Doctrine\Common\EventSubscriber,
	Doctrine\ORM\Event\OnFlushEventArgs,
	Doctrine\ORM\Event\PostFlushEventArgs,
	Doctrine\ORM\Events,
	Doctrine\ORM\UnitOfWork;

class Listener implements EventSubscriber
{
	protected $_searches = [];

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
			if (!$entity instanceof Searchable) {
				continue;
			}
			$search = $em->getRepository('Ayre\Search')->fetch($entity);
			$search->content = implode(' ', $entity->searchContent);
			$this->_searches[] = $search;
		}
	}

	public function postFlush(PostFlushEventArgs $args)
	{
		if (!count($this->_searches)) {
			return;
		}

		$em = $args->getEntityManager();

		while (count($this->_searches)) {
			$search = array_shift($this->_searches);
			if (!isset($search->id)) {
				$search->class_id = $search->class_obj->id;
				$em->persist($search);
			}
		}

		$em->flush();
	}
}