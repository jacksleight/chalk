<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository,
    Chalk\Core\Behaviour\Searchable;

class Setting extends Repository
{
    protected $_sort    = 'name';
    protected $_indexBy = 'name';
}