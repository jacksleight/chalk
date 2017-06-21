<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

namespace Chalk\Core\Backend\Model\Content;

use Chalk\Chalk;
use Chalk\Core\Backend\Model\Entity\Update as EntityUpdate;

class Update extends EntityUpdate
{
	protected $nodes;
	protected $nodesList;

	protected static function _defineMetadata($class)
	{
		return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
                'nodes' => array(
                    'type'      => 'array',
                    'nullable'  => true,
                ),
                'nodesList' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
			)
		));
	}

    public function nodes(array $nodes = null)
    {
        if (func_num_args() > 0) {
            $this->nodesList = implode('.', $nodes);
            return $this;
        }
        return isset($this->nodesList)
            ? explode('.', $this->nodesList)
            : [];
    }
}