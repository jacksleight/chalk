<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Entity;

abstract class Doctrine extends \Js\Entity
{
	public static function loadMetadata(\Doctrine\ORM\Mapping\ClassMetadata $classMetadata)
	{
		$md = static::_getMetadata();

		$repositoryClass = get_called_class() . '\Repository';
		if (class_exists($repositoryClass)) {
			$classMetadata->setCustomRepositoryClass($repositoryClass);
		}

		$classMetadata->addLifecycleCallback('prePersist', \Doctrine\ORM\Events::prePersist);
		$classMetadata->addLifecycleCallback('preFlush', \Doctrine\ORM\Events::preFlush);
		$classMetadata->addLifecycleCallback('preUpdate', \Doctrine\ORM\Events::preUpdate);
		$classMetadata->addLifecycleCallback('prePersistUpdate', \Doctrine\ORM\Events::prePersist);
		$classMetadata->addLifecycleCallback('prePersistUpdate', \Doctrine\ORM\Events::preUpdate);
		$classMetadata->addLifecycleCallback('preRemove', \Doctrine\ORM\Events::preRemove);
		$classMetadata->addLifecycleCallback('postLoad', \Doctrine\ORM\Events::postLoad);
		$classMetadata->addLifecycleCallback('postPersist', \Doctrine\ORM\Events::postPersist);
		$classMetadata->addLifecycleCallback('postFlush', \Doctrine\ORM\Events::postFlush);
		$classMetadata->addLifecycleCallback('postUpdate', \Doctrine\ORM\Events::postUpdate);
		$classMetadata->addLifecycleCallback('postPersistUpdate', \Doctrine\ORM\Events::postPersist);
		$classMetadata->addLifecycleCallback('postPersistUpdate', \Doctrine\ORM\Events::postUpdate);
		$classMetadata->addLifecycleCallback('postRemove', \Doctrine\ORM\Events::postRemove);
				
		if (!$classMetadata->isInheritanceTypeSingleTable() && isset($md['table'])) {			
			$classMetadata->setTableName($md['table']);
		}

		if ($classMetadata->isInheritanceTypeNone() && isset($md['inheritance'])) {
			$classMetadata->setInheritanceType($md['inheritance']['type'] == 'singleTable'
				? \Doctrine\ORM\Mapping\ClassMetadataInfo::INHERITANCE_TYPE_SINGLE_TABLE
				: \Doctrine\ORM\Mapping\ClassMetadataInfo::INHERITANCE_TYPE_JOINED);
			$classMetadata->setDiscriminatorColumn(array(
				'name'		=> $md['inheritance']['column'][0],
				'type'		=> $md['inheritance']['column'][1],
				'length'	=> $md['inheritance']['column'][2],
			));
			$classMetadata->setDiscriminatorMap($md['inheritance']['map']);
		}

		foreach ($md['fields'] as $name => $field) {
			if ($classMetadata->isInheritedField($name) || !$field['persist']) {
				continue;
			}
			$classMetadata->mapField(array(
				'id'		=> $field['id'],
				'fieldName'	=> $name,
				'columnName'=> isset($field['column']) ? $field['column'] : $name,
				'type'		=> $field['type'],
				'length'	=> $field['length'],
				'nullable'	=> $field['nullable'],
				'unique'	=> $field['unique'],
				'precision'	=> $field['precision'],
				'scale'		=> $field['scale'],
			));
			if ($field['auto']) {
				$classMetadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_AUTO);
			}
		}

		foreach ($md['associations'] as $name => $assoc) {
			if ($classMetadata->isInheritedAssociation($name) || !$assoc['persist']) {
				continue;
			}
			switch ($assoc['type']) {
				case 'oneToMany':
					$classMetadata->mapOneToMany(array(
						'fieldName'		=> $name,
						'indexBy'		=> 'id',
						'targetEntity'	=> $assoc['entity'],
						'cascade'		=> $assoc['cascade'],
						'mappedBy'		=> $assoc['inverse'],
						'joinColumns'	=> array(array(
							'name'					=> "id",
							'referencedColumnName'	=> "{$assoc['inverse']}Id",
						)),
					));
				break;
				case 'oneToOne':
					$classMetadata->mapOneToOne(array(
						'fieldName'		=> $name,
						'targetEntity'	=> $assoc['entity'],
						'cascade'		=> $assoc['cascade'],
						'mappedBy'		=> !$assoc['owner']  ? $assoc['inverse'] : null,
						'inversedBy'	=> $assoc['owner'] ? $assoc['inverse'] : null,
						'joinColumns'	=> $assoc['owner'] ? array(array(
							'name'					=> "{$name}Id",
							'referencedColumnName'	=> "id",
							'nullable'				=> $assoc['nullable'],
							'onUpdate'				=> $assoc['onUpdate'],
							'onDelete'				=> $assoc['onDelete'],
						)) : null,
					));
				break;
				case 'manyToMany':
					$classMetadata->mapManyToMany(array(
						'fieldName'		=> $name,
						'indexBy'		=> 'id',
						'targetEntity'	=> $assoc['entity'],
						'cascade'		=> $assoc['cascade'],
						'mappedBy'		=> !$assoc['owner'] ? $assoc['inverse'] : null,
						'joinTable'		=> $assoc['owner'] ? array(
							'name' => $assoc['table']['name'],
							'joinColumns'	=> array(array(
								'name'					=> "{$assoc['table']['join']['name']}Id",
								'referencedColumnName'	=> "id",
								'onUpdate'				=> $assoc['table']['join']['onUpdate'],
								'onDelete'				=> $assoc['table']['join']['onDelete'],
							)),
							'inverseJoinColumns' => array(array(
								'name'					=> "{$assoc['table']['inverseJoin']['name']}Id",
								'referencedColumnName'	=> "id",
								'onUpdate'				=> $assoc['table']['inverseJoin']['onUpdate'],
								'onDelete'				=> $assoc['table']['inverseJoin']['onDelete'],
							)),
						) : null,
					));
				break;
				case 'manyToOne':
					$classMetadata->mapManyToOne(array(
						'fieldName'		=> $name,
						'targetEntity'	=> $assoc['entity'],
						'cascade'		=> $assoc['cascade'],
						'inversedBy'	=> $assoc['inverse'],
						'joinColumns'	=> array(array(
							'name'					=> "{$name}Id",
							'referencedColumnName'	=> "id",
							'nullable'				=> $assoc['nullable'],
							'onUpdate'				=> $assoc['onUpdate'],
							'onDelete'				=> $assoc['onDelete'],
						)),
					));				
				break;
			}			
		}
	}

	public function prePersist()
	{}

	public function preFlush()
	{}

	public function preUpdate()
	{}

	public function prePersistUpdate()
	{}

	public function preRemove()
	{}

	public function postLoad()
	{}

	public function postPersist()
	{}

	public function postFlush()
	{}

	public function postUpdate()
	{}

	public function postPersistUpdate()
	{}

	public function postRemove()
	{}
	
	public function isPersisted()
	{
		$uow = \App::$helper->em->getUnitOfWork();
		return $uow->getEntityState($this) == \Doctrine\ORM\UnitOfWork::STATE_MANAGED;
	}

	public function getChangeSet()
	{
		$uow = \App::$helper->em->getUnitOfWork();
		$uow->computeChangeSets();
		return $uow->getEntityChangeSet($this);
	}
}
