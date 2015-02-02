<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository;

class User extends Repository
{
    protected $_alias = 'u';

    public function query(array $criteria = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = parent::query($criteria, $sort, $limit, $offset);

        $criteria = $criteria + [
			'emailAddress'	=> null,
			'token'			=> null,
        ];
        
        if (isset($criteria['emailAddress'])) {
            $query
                ->andWhere("u.emailAddress = :emailAddress")
                ->setParameter('emailAddress', $criteria['emailAddress']);
        }
        if (isset($criteria['token'])) {
            $query
                ->andWhere("u.token = :token AND u.tokenDate > UTC_TIMESTAMP()")
                ->setParameter('token', $criteria['token']);
        }

        // This is required to prevent proxy objects being used when editing users
        $query
            ->addSelect('cu', 'mu')
            ->leftJoin('u.createUser', 'cu')
            ->leftJoin('u.modifyUser', 'mu');

        return $query;
    }
}