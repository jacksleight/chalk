<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

namespace Chalk\Core\Backend\Model\Structure\Node;

use Chalk\Chalk;
use Chalk\Core\Backend\Model\Entity\Update as EntityUpdate;

class Update extends EntityUpdate
{
    protected $node;
    protected $nodeType;

	protected static function _defineMetadata($class)
	{
		return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
                'node' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'nodeType' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
			)
		));
	}
}