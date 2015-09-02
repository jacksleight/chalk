<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository,
    Chalk\Behaviour\Searchable;

class User extends Repository
{
    use Searchable\Repository;

    public function build(array $params = array())
    {
        $query = parent::build($params);

        $params = $params + [
			'emailAddress'	=> null,
			'token'			=> null,
        ];
        
        if (isset($params['emailAddress'])) {
            $query
                ->andWhere("u.emailAddress = :emailAddress")
                ->setParameter('emailAddress', $params['emailAddress']);
        }
        if (isset($params['token'])) {
            $query
                ->andWhere("u.token = :token AND u.tokenDate > UTC_TIMESTAMP()")
                ->setParameter('token', $params['token']);
        }

        $this->searchableQueryModifier($query, $params);

        return $query;
    }
}