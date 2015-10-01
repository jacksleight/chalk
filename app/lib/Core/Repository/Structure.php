<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository,
	Chalk\Core\Menu,
    Chalk\Behaviour\Publishable,
	Chalk\Core\Structure as CoreStructure;

class Structure extends Repository
{
	use Publishable\Repository;

    public function build(array $params = array())
    {
        $query = parent::build($params);

        $query
            ->addSelect("n", "c")
            ->leftJoin("s.nodes", "n")
            ->leftJoin("n.content", "c")
            ->andWhere("n.left = 0");

        return $query;
    }
}