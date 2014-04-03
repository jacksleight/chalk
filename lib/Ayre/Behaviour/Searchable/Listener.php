<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre\Behaviour\Searchable;

use Ayre,
	Ayre\Entity,
	Ayre\Behaviour\Searchable,
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
			if (!$entity instanceof Searchable) {
				continue;
			}
			$changeSet	= $uow->getEntityChangeSet($entity);
			$fields		= $entity->searchFields;
			$changes	= array_intersect($fields, array_keys($changeSet));
			if (!count($changes)) {
				continue;
			}
			$search  = $em->getRepository('Ayre\Entity\Search')->fetch($entity);
			if (!isset($search)) {
				$search				= new Entity\Search();
				$search->class		= Ayre::resolve($entity)->short;
				$search->class_obj	= $entity;
			}
			$content = [];
			foreach ($fields as $field) {
				$value = $entity->$field;
				if (is_array($value)) {
					$temp = (object) ['temp' => []];
					array_walk_recursive($value, function($value, $name, $temp) {
						$temp->temp[] = $value;
					}, $temp);
					$value = implode(' ', $temp->temp);
				}
				$content[] = $value;
			}
			$search->content = implode(' ', $content);
			$this->_updates[] = $search;
		}

		$entities = array_merge(
			$uow->getScheduledEntityDeletions()
		);
		foreach ($entities as $entity) {
			if (!$entity instanceof Searchable) {
				continue;
			}
			$search  = $em->getRepository('Ayre\Entity\Search')->fetch($entity);
			if (!isset($search)) {
				continue;
			}
			$this->_deletions[] = $search;
		}
	}

	public function postFlush(PostFlushEventArgs $args)
	{
		if (!count($this->_updates) && !count($this->_deletions)) {
			return;
		}

		$em = $args->getEntityManager();

		while (count($this->_updates)) {
			$search = array_shift($this->_updates);
			if (!isset($search->id)) {
				$search->class_id = $search->class_obj->id;
				$em->persist($search);
			}
		}

		while (count($this->_deletions)) {
			$search = array_shift($this->_deletions);
			$em->remove($search);
		}	

		$em->flush();
	}
}