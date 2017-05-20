<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class Alias extends Content
{
    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

        $query
            ->addSelect("ac")
            ->leftJoin("a.content", "ac");

        return $query;
    }
}