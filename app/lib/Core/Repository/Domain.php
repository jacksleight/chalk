<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class Domain extends Repository
{
	protected $_alias = 'd';

    public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::query($criteria, $sort, $limit, $offset);

        $query
            ->addSelect("s", "n", "c")
            ->leftJoin("d.structure", "s")
            ->leftJoin("s.nodes", "n")
            ->leftJoin("n.content", "c")
            ->andWhere("n.left = 0");

        return $query;
    }
}