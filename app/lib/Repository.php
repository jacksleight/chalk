<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\Query,
    Doctrine\ORM\Tools\Pagination\Paginator;

class Repository extends EntityRepository
{
    protected $_alias = 'e';
    protected $_sort  = null;
    protected $_limit = 1;

    public function create()
    {
        $args = func_get_args();
        $reflection = new \ReflectionClass($this->getClassName());
        return $reflection->newInstanceArgs($args);
    }

    public function id($id, array $params = array())
    {
        return $this->query(array_merge($params, ['ids' => [$id]]))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function slug($slug, array $params = array())
    {
        return $this->query(array_merge($params, ['slugs' => [$slug]]))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function one(array $params = array())
    {   
        return $this->query($params)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function all(array $params = array())
    {   
        return $this->query($params)
            ->getQuery()
            ->getResult();
    }

    public function paged(array $params = array())
    {   
        $params = $params + [
            'limit' => $this->_limit,
            'page'  => 1,
        ];

        $query = $this->query($params + [
            'offset' => $params['limit'] * ($params['page'] - 1)
        ]);
        
        return new Paginator($query);
    }

    public function count(array $params = array())
    {   
        return $this->query($params)
            ->select("COUNT({$this->_alias})")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function query(array $params = array())
    {
        $query = $this->createQueryBuilder($this->_alias);

        $params = $params + [
            'ids'    => null,
            'slugs'  => null,
            'sort'   => null,
            'limit'  => null,
            'offset' => null,
        ];

        $sorts = isset($params['sort'])
            ? $params['sort']
            : $this->_sort;
        if (isset($sorts)) {
            if ($sorts == 'random') {
                $query->orderBy('RAND()');
            } else {
                if (!is_array($sorts)) {
                    $sorts = [$sorts];
                }
                if (!is_array($sorts[0])) {
                    $sorts = [$sorts];
                }
                foreach ($sorts as $i => $sort) {
                    $method = $i
                        ? 'addOrderBy'
                        : 'orderBy';
                    $query->$method($sort[0], isset($sort[1]) ? $sort[1] : 'ASC');
                }
            }
        }
        
        if (isset($params['ids'])) {
            $query
                ->andWhere("{$this->_alias}.id IN (:ids)")
                ->setParameter('ids', $params['ids']);
            if (!isset($params['sort'])) {
                $query->orderBy("FIELD({$this->_alias}.id, :ids)");
            }
        } else if (isset($params['slugs'])) {
            $query
                ->andWhere("{$this->_alias}.slug IN (:slugs)")
                ->setParameter('slugs', $params['slugs']);
            if (!isset($params['sort'])) {
                $query->orderBy("FIELD({$this->_alias}.slug, :slugs)");
            }
        }
        
        if (isset($params['limit'])) {
            $query->setMaxResults($params['limit']);
        }
        if (isset($params['offset'])) {
            $query->setFirstResult($params['offset']);
        }
        
        return $query;
    }
}