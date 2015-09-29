<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class Alias extends Content
{
    public function build(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::build($criteria, $sort, $limit, $offset);

        $query
            ->addSelect("ac")
            ->leftJoin("a.content", "ac");

        return $query;
    }
}