<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository,
	Chalk\Core\Menu,
    Chalk\Behaviour\Publishable,
	Chalk\Core\Structure as CoreStructure;

class Structure extends Repository
{
	use Publishable\Repository {
        Publishable\Repository::queryModifier as publishableQueryModifier;
    }

    protected $_alias = 's';

    public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::query($criteria, $sort, $limit, $offset);

        $query
            ->addSelect("n", "c", "cv")
            ->leftJoin("s.nodes", "n")
            ->leftJoin("n.content", "c")
            ->leftJoin("c.versions", "cv")
            ->andWhere("n.left = 0")
            ->andWhere("cv.next IS NULL");

        $this->publishableQueryModifier($query, $criteria);

        return $query;
    }
}