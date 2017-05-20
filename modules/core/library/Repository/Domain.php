<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class Domain extends Repository
{
    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

        if ($extra) {
            $query
                ->addSelect("s", "n", "c")
                ->leftJoin("d.structures", "s")
                ->leftJoin("s.nodes", "n", "WITH", "n.left = 0")
                ->leftJoin("n.content", "c");
        }

        return $query;
    }
}