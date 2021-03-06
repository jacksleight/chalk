<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Behaviour\Publishable;

use Chalk\Chalk,
	Chalk\Core\Behaviour\Publishable,
	Doctrine\Common\EventSubscriber,
	Doctrine\ORM\Event\LifecycleEventArgs,
	Doctrine\ORM\Events,
    Carbon\Carbon;

class Listener implements EventSubscriber
{
	public function getSubscribedEvents()
	{
		return [
			Events::prePersist,
			Events::preUpdate,
		];
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Publishable) {
			return;
		}

		if ($entity->status == Chalk::STATUS_PUBLISHED) {
			if (!isset($entity->publishDate)) {
				$entity->publishDate = new Carbon();
			}
		} else if ($entity->status == Chalk::STATUS_ARCHIVED) {
			if (!isset($entity->publishDate)) {
				$entity->publishDate = new Carbon();
			}
			if (!isset($entity->archiveDate)) {
				$entity->archiveDate = new Carbon();
			}
		}
	}

	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		
		if (!$entity instanceof Publishable) {
			return;
		}

		if (!$args->hasChangedField('status')) {
			return;
		}

		$old = $args->getOldValue('status');
		$new = $args->getNewValue('status');

		if ($old == Chalk::STATUS_DRAFT) {
			if ($new == Chalk::STATUS_PUBLISHED) {
				if (!isset($entity->publishDate)) {
					$entity->publishDate = new Carbon();
				}
			} else if ($new == Chalk::STATUS_ARCHIVED) {
				if (!isset($entity->publishDate)) {
					$entity->publishDate = new Carbon();
				}
				if (!isset($entity->archiveDate)) {
					$entity->archiveDate = new Carbon();
				}
			}
		} else if ($old == Chalk::STATUS_PUBLISHED) {
			if ($new == Chalk::STATUS_ARCHIVED) {
				if (!isset($entity->archiveDate)) {
					$entity->archiveDate = new Carbon();
				}
			}
		} else if ($old == Chalk::STATUS_ARCHIVED) {
			$entity->archiveDate = null;
		}
	}
}