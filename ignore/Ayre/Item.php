<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

abstract class Item extends \Js\Entity\Doctrine
{
	protected $revisions;
	protected $nodes;

	protected $type;
	protected $subtype;
	
	protected $id;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'item',
			'inheritance' => array(
				'type'		=> 'singleTable',
				'column'	=> array('_discriminator', 'string', 25),
				'map'		=> \Ayre::$instance->app->getItemTypeMap(),
			),
			'fields' => array(
				'type' => array(
					'type'		=> 'string',
					'length'	=> 255,
				),
				'subtype' => array(
					'type'		=> 'string',
					'length'	=> 255,
					'nullable'	=> true,
				),
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
			),
			'associations' => array(
				'revisions' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "{$class}\Revision",
					'inverse'	=> 'item',
					'cascade'	=> array('remove'),
				),
				'nodes' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "Ayre\Tree\Revision\Node",
					'inverse'	=> 'item',
				),
			),
		);
	}
	
	public static function search($phrase)
	{
		$conn = \Ayre::$instance->em->getConnection();
		
		$phrase = $conn->quote($phrase);
		return \JS\array_column($conn->query("
			SELECT s.id,
				MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE) AS score
			FROM search AS s
			WHERE MATCH(s.content) AGAINST ({$phrase} IN BOOLEAN MODE)
			ORDER BY score DESC
		")->fetchAll(), 'id');
	}

	public function __construct()
	{
		parent::__construct();

		$this->createRevision();
	}

	public function createRevision()
	{
		$class = get_class($this) . '\Revision';
		$revision = new $class();
		$revision->item = $this;
		$this->revisions->add($revision);
		return $revision;
	}

	public function cloneRevision()
	{
		$revision = $this->revision;
		\Ayre::$instance->em->refresh($revision);
		$revision = clone $revision;
		$this->revisions->add($revision);
		return $revision;
	}

	public function removeRevision(\Ayre\Item\Revision $revision)
	{
		$this->revisions->removeElement($revision);
	}

	public function getName()
	{
		return $this->revisions->last()->versions->first()->name;
	}
}