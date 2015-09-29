<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk,
    Chalk\Repository,
    Chalk\Behaviour\Publishable,
    Chalk\Behaviour\Searchable,
    DateTime;

class Content extends Repository
{
    use Publishable\Repository, 
        Searchable\Repository;

    protected $_sort = ['modifyDate', 'DESC'];

    public function build(array $params = array())
    {
        $query = parent::build($params);

        $params = $params + [
            'types'         => null,
            'subtypes'      => null,
            'createDateMin' => null,
            'createDateMax' => null,
            'modifyDateMin' => null,
            'modifyDateMax' => null,
            'createUsers'   => null,
            'statuses'      => null,
        ];
             
        if (isset($params['types']) && count($params['types'])) {
            $types = $this->parseTypes($params['types']);
            $lines = [];
            $i = 0;
            foreach ($types as $class => $subtypes) {
                $line = "{$this->alias()} INSTANCE OF {$class}";
                if (isset($subtypes) && count($subtypes)) {
                    $line .= " AND {$this->alias()}.subtype IN (:types_{$i}_subtypes)";
                    $query->setParameter("types_{$i}_subtypes", $subtypes);
                }
                $lines[] = "({$line})";
                $i++;
            }
            $query->andWhere(implode(' OR ', $lines));
        }   
        if (isset($params['subtypes']) && count($params['subtypes'])) {
            $query
                ->andWhere("{$this->alias()}.subtype IN (:subtypes)")
                ->setParameter('subtypes', $params['subtypes']);
        }

        if (isset($params['createDateMin'])) {
            $createDateMin = $params['createDateMin'] instanceof DateTime
                ? $params['createDateMin']
                : new DateTime($params['createDateMin']);
            $query
                ->andWhere("{$this->alias()}.createDate >= :createDateMin")
                ->setParameter('createDateMin', $createDateMin);
        }
        if (isset($params['createDateMax'])) {
            $createDateMax = $params['createDateMax'] instanceof DateTime
                ? $params['createDateMax']
                : new DateTime($params['createDateMax']);
            $query
                ->andWhere("{$this->alias()}.createDate <= :createDateMax")
                ->setParameter('createDateMax', $createDateMax);
        }

        if (isset($params['modifyDateMin'])) {
            $modifyDateMin = $params['modifyDateMin'] instanceof DateTime
                ? $params['modifyDateMin']
                : new DateTime($params['modifyDateMin']);
            $query
                ->andWhere("{$this->alias()}.modifyDate >= :modifyDateMin")
                ->setParameter('modifyDateMin', $modifyDateMin);
        }
        if (isset($params['modifyDateMax'])) {
            $modifyDateMax = $params['modifyDateMax'] instanceof DateTime
                ? $params['modifyDateMax']
                : new DateTime($params['modifyDateMax']);
            $query
                ->andWhere("{$this->alias()}.modifyDate <= :modifyDateMax")
                ->setParameter('modifyDateMax', $modifyDateMax);
        }

        if (isset($params['createUsers'])) {
            $query
                ->andWhere("{$this->alias()}.createUser IN (:createUsers)")
                ->setParameter('createUsers', $params['createUsers']);
        }
        if (isset($params['statuses']) && count($params['statuses'])) {
            $query
                ->andWhere("{$this->alias()}.status IN (:statuses)")
                ->setParameter('statuses', $params['statuses']);
        }

        $this->publishableQueryModifier($query, $params);
        $this->searchableQueryModifier($query, $params);

        // echo $query->getQuery()->getSql();
        // die;

        return $query;
    }

    public function subtypes(array $params = array(), array $opts = array())
    {
        $query = $this->build($params)
            ->select("{$this->alias()}.subtype AS subtype, COUNT({$this->alias()}) AS total")
            ->groupBy("{$this->alias()}.subtype")
            ->andWhere("{$this->alias()}.subtype IS NOT NULL");
        $query = $this->prepare($query, [
            'hydrate' => Repository::HYDRATE_ARRAY
        ] + $opts);
        return $this->execute($query);
    }

    public function parseTypes($types)
    {
        if (!is_array($types)) {
            $types = [$types];
        }
        $temp = [];
        foreach ($types as $type => $subtypes) {
            if (is_numeric($type)) {
                $type = $subtypes;
                $subtypes = [];
            } else if (!is_array($subtypes)) {
                $subtypes = [$subtypes];
            }
            $info = Chalk::info($type);
            $classes = array_merge(
                [$info->class],
                $this->_em->getClassMetadata($info->class)->subClasses
            );
            foreach ($classes as $class) {
                $temp[$class] = $subtypes;
            }
        }
        return $temp;
    }
}