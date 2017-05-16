<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\Query,
    Doctrine\ORM\Tools\Pagination\Paginator;

class Repository extends EntityRepository
{
    const SORT_RANDOM           = 'random';

    const HYDRATE_OBJECT        = Query::HYDRATE_OBJECT;
    const HYDRATE_ARRAY         = 'coast_array';
    const HYDRATE_SCALAR        = Query::HYDRATE_SCALAR;
    const HYDRATE_SINGLE_SCALAR = Query::HYDRATE_SINGLE_SCALAR;

    const FETCH_ALL             = 'all';
    const FETCH_ALL_PAGED       = 'allPaged';
    const FETCH_ONE             = 'one';
    const FETCH_ONE_UNIQUE      = 'oneUnique';

    protected $_sort    = 'id';
    protected $_limit   = 1;
    protected $_indexBy = null;

    public function create(array $array = array())
    {
        $args = func_get_args();
        $reflection = new \ReflectionClass($this->getClassName());
        $entity = $reflection->newInstanceArgs($args);
        $entity->fromArray($array);
        return $entity;
    }

    public function id($id, array $params = array(), array $opts = array())
    {
        $query = $this->build(['ids' => [$id]] + $params);
        $query = $this->prepare($query, $opts);  
        return $this->fetch($query, self::FETCH_ONE_UNIQUE);
    }

    public function slug($slug, array $params = array(), array $opts = array())
    {
        $query = $this->build(['slugs' => [$slug]] + $params);
        $query = $this->prepare($query, $opts);  
        return $this->fetch($query, self::FETCH_ONE);
    }

    public function one(array $params = array(), array $opts = array(), $mode = self::FETCH_ONE)
    {
        $query = $this->build($params);
        $query = $this->prepare($query, $opts);  
        return $this->fetch($query, $mode);
    }

    public function all(array $params = array(), array $opts = array(), $mode = self::FETCH_ALL)
    {   
        $query = $this->build($params);
        $query = $this->prepare($query, $opts);
        return $this->fetch($query, $mode);
    }

    public function count(array $params = array(), array $opts = array())
    {   
        $query = $this->build($params)
            ->select("COUNT(DISTINCT {$this->alias()})");
        $query = $this->prepare($query, [
            'hydrate' => Repository::HYDRATE_SINGLE_SCALAR,
        ] + $opts);
        return $this->fetch($query);
    }

    public function build(array $params = array())
    {
        $query = $this->createQueryBuilder($this->alias(), isset($this->_indexBy) ? "{$this->alias()}.{$this->_indexBy}" : null);

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
            if (!is_array($sorts)) {
                $sorts = explode(',', $sorts);
            }
            if (!is_array($sorts[0])) {
                $sorts = [$sorts];
            }
            foreach ($sorts as $i => $sort) {
                $method = $i
                    ? 'addOrderBy'
                    : 'orderBy';
                if ($sort[0] == self::SORT_RANDOM) {
                    $sort[0] = 'RAND()';
                } else if (strpos($sort[0], '.') === false && strpos($sort[0], '(') === false) {
                    $sort[0] = "{$this->alias()}.{$sort[0]}";
                }
                $query->$method($sort[0], isset($sort[1]) ? $sort[1] : 'ASC');
            }
        }
        
        if (isset($params['ids'])) {
            $query
                ->andWhere("{$this->alias()}.id IN (:ids)")
                ->setParameter('ids', $params['ids']);
            if (!isset($params['sort'])) {
                $query->orderBy("FIELD({$this->alias()}.id, :ids)");
            }
        } else if (isset($params['slugs'])) {
            $query
                ->andWhere("{$this->alias()}.slug IN (:slugs)")
                ->setParameter('slugs', $params['slugs']);
            if (!isset($params['sort'])) {
                $query->orderBy("FIELD({$this->alias()}.slug, :slugs)");
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
            $query->setHydrationMode($opts['hydrate']);
        }
        if ($opts['cache']) {
            $query->useResultCache(true);
            if (is_numeric($opts['cache'])) {
                $query->setResultCacheLifetime($opts['cache']);
            }
        }

        return $query;
    }

    public function fetch(Query $query, $mode = self::FETCH_ALL)
    {
        if ($mode == self::FETCH_ALL) {
            if ($query->getMaxResults() !== null && $query->getHydrationMode() != self::HYDRATE_SINGLE_SCALAR) {
                return (new Paginator($query))->getIterator()->getArrayCopy();
            } else {
                return $query->execute();
            }
        } else if ($mode == self::FETCH_ALL_PAGED) {
            return new Paginator($query);
        } else if ($mode == self::FETCH_ONE) {
            $query->setMaxResults(1);
            if ($query->getHydrationMode() != self::HYDRATE_SINGLE_SCALAR) {
                return (new Paginator($query))->getIterator()->current();
            } else {
                return $query->getOneOrNullResult();
            }         
        } else if ($mode == self::FETCH_ONE_UNIQUE) {
            return $query->getOneOrNullResult();
        }
    }

    public function alias()
    {
        $class = get_class($this);
        $class = substr($class, strrpos($class, '\\') + 1);
        return strtolower($class[0]);
    }
}