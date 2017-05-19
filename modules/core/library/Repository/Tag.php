<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository,
    Chalk\Core\Behaviour\Searchable;

class Tag extends Repository
{
    use Searchable\Repository;
    
    protected $_sort = ['name', 'ASC'];

    public function build(array $params = array(), $one = false)
    {
        $query = parent::build($params);

        $this->searchable_modify($query, $params);

        return $query;
    }
}