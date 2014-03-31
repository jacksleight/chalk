<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre;

class Tree extends \Js\Entity\Doctrine
{
	protected $revisions;
	protected $domains;
	protected $menus;

	protected $id;
	protected $name;

	protected static function _defineMetadata($class)
	{
		return array(
			'table' => 'tree',
			'fields' => array(
				'id' => array(
					'id'		=> true,
					'auto'		=> true,
					'type'		=> 'integer',
				),
				'name' => array(
					'type'		=> 'string',
				),
			),
			'associations' => array(
				'revisions' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "Ayre\Tree\Revision",
					'inverse'	=> 'tree',
					'cascade'	=> array('remove'),
				),
				'domains' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "Ayre\Domain",
					'inverse'	=> 'tree',
				),
				'menus' => array(
					'type'		=> 'oneToMany',
					'entity'	=> "Ayre\Menu",
					'inverse'	=> 'tree',
				),
			),
		);
	}

	public function __construct()
	{
		parent::__construct();

		$this->createRevision();
	}

	public function createRevision()
	{
		$revision = new \Ayre\Tree\Revision();
		$revision->tree = $this;
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

	/* Default revision is always the last one, revisions must always be sorted by revision.id ASC */
	public function getRevision()
	{
		return $this->revisions->last();
	}
}