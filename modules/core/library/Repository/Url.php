<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class Url extends Content
{
    protected $_sort = ['createDate', 'DESC'];
    
    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

        $params = $params + [
            'url' => null,
        ];
             
        if (isset($params['url'])) {
            $query
                ->andWhere("u.url = :url")
                ->setParameter('url', $params['url']);
        }

        return $query;
    }
}