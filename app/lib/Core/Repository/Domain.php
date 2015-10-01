<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class Domain extends Repository
{
    public function build(array $params = array())
    {
        $query = parent::build($params);

        $query
            ->addSelect("s", "n", "c")
            ->leftJoin("d.structures", "s")
            ->leftJoin("s.nodes", "n", "n.left = 0 AND n.content IS NOT NULL")
            ->leftJoin("n.content", "c");

        return $query;
    }
}