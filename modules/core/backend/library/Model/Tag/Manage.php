<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Model\Tag;

use Chalk\Core\Backend\Model;
use Coast\Validator;

class Manage extends Model
{
    protected $type = 'add';
    protected $tagNames;
    protected $tagNamesList;

    protected static function _defineMetadata($class)
    {
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
            'fields' => array(
                'type' => array(
                    'type'   => 'string',
                    'values' => [
                        'add'       => 'Add Tags',
                        'remove'    => 'Remove Tags',
                    ],
                ),
                'tagNames' => array(
                    'type'      => 'array',
                    'nullable'  => true,
                ),
                'tagNamesList' => array(
                    'type'      => 'string',
                ),
            ),
        ));
    }

    public function tagNames(array $tagNames = array())
    {
        if (func_num_args() > 0) {
            $this->tagNamesList = implode('|', $tagNames);
            return $this;
        }
        return isset($this->tagNamesList)
            ? explode('|', $this->tagNamesList)
            : [];
    }
}