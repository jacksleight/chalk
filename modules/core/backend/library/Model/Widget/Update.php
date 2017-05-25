<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Model\Widget;

use Chalk\Chalk;
use Chalk\Core\Backend\Model;
use	Doctrine\Common\Collections\ArrayCollection;

class Update extends Model
{
    protected $method;
    protected $type;
    protected $state;

    protected static function _defineMetadata($class)
    {
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
            'fields' => array(
                'method' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'type' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
                'state' => array(
                    'type'      => 'string',
                    'nullable'  => true,
                ),
            ),
        ));
    }
}
