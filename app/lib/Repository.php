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
    const SORT_RANDOM           = 'random';

    const HYDRATE_OBJECT        = 'object';
    const HYDRATE_ARRAY         = 'array';
    const HYDRATE_SCALAR        = 'scalar';
    const HYDRATE_SINGLE_SCALAR = 'singleScalar';

    protected $_alias = 'e';
    protected $_sort  = null;
    protected $_limit = 1;

    public function create()
    {
        $args = func_get_args();
        $reflection = new \ReflectionClass($this->getClassName());
        return $reflection->newInstanceArgs($args);
    }

    public function id($id, array $params = array(), array $opts = array())
    {
        $query = $this->query(['ids' => [$id]] + $params);
        
        $query = $this->prepare($query, $opts);    
        return $query->getOneOrNullResult();
    }

    public function slug($slug, array $params = array(), array $opts = array())
    {
        $query = $this->query(['slugs' => [$slug]] + $params);
        
        $query = $this->prepare($query, $opts);    
        return $query->getOneOrNullResult();
    }

    public function one(array $params = array(), array $opts = array())
    {   
        $query = $this->query($params);
        
        $query = $this->prepare($query, $opts);    
        return $query->getOneOrNullResult();
    }

    public function all(array $params = array(), array $opts = array())
    {   
        $query = $this->query($params);
        
        $query = $this->prepare($query, $opts);    
        return $query->getResult();
    }

    public function paged(array $params = array(), array $opts = array())
    {   
        $query = $this->query($params + [
            'limit' => $this->_limit,
            'page'  => 1,
        ]);
        
        $query = $this->prepare($query, $opts);
        return new Paginator($query);
    }

    public function count(array $params = array(), array $opts = array())
    {   
        $query = $this->query($params)
            ->select("COUNT({$this->_alias})");
        
        $query = $this->prepare($query, $opts);
        return $query->getSingleScalarResult();
    }

    public function query(array $params = array())
    {
        $query = $this->createQueryBuilder($this->_alias);

        if (isset($params['limit']) && isset($params['page'])) {
            $params['offset'] = $params['limit'] * ($params['page'] - 1);
        }
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
            if ($sorts == self::SORT_RANDOM) {
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

    public function prepare(QueryBuilder $query, array $opts = array())
    {
        $query = $query->getQuery();

        $opts = $opts + [
            'hydrate' => null,
            'cache'   => false,
        ];

        if (isset($opts['hydrate'])) {
            $modes = [
                self::HYDRATE_OBJECT        => Query::HYDRATE_OBJECT,
                self::HYDRATE_ARRAY         => Query::HYDRATE_ARRAY,
                self::HYDRATE_SCALAR        => Query::HYDRATE_SCALAR,
                self::HYDRATE_SINGLE_SCALAR => Query::HYDRATE_SINGLE_SCALAR,    
            ];
            $query->setHydrationMode($modes[$opts['hydrate']]);
        }
        if (isset($opts['cache'])) {
            $query->useResultCache(true);
            if (is_numeric($opts['cache'])) {
                $query->setResultCacheLifetime($opts['cache']);
            }
        }

        return $query;
    }
}