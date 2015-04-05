<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class Domain extends Repository
{
	protected $_alias = 'd';

    public function query(array $params = array())
    {
        $query = parent::query($params);

        $query
            ->addSelect("s", "n", "c")
            ->leftJoin("d.structures", "s")
            ->leftJoin("s.nodes", "n", "n.left = 0 AND n.content IS NOT NULL")
            ->leftJoin("n.content", "c");

        return $query;
    }
}