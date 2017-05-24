<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Chalk;
use Chalk\Info;
use Chalk\Repository;
use Chalk\Core\Behaviour\Publishable;
use Chalk\Core\Behaviour\Searchable;
use Chalk\Core\Behaviour\Tagable;
use DateTime;

class Content extends Repository
{
    use Publishable\Repository;
    use Searchable\Repository;
    use Tagable\Repository;

    protected $_sort = ['name', 'ASC'];

    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

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

        if (isset($params['filtersInfo'])) {
            $types = [];
            foreach ($params['filtersInfo'] as $info) {
                $types[$info->class] = $info->subs;
            }
            $params['types'] = $types;
        }
             
        if (isset($params['types']) && count($params['types'])) {
            $all = [];
            foreach ($types as $name => $subtypes) {
                $info = Chalk::info($name);
                $classes = array_merge(
                    [$info->class],
                    $this->_em->getClassMetadata($info->class)->subClasses
                );
                foreach ($classes as $class) {
                    $all[$class] = $subtypes;
                }
            }
            $lines = [];
            $i = 0;
            foreach ($all as $class => $subtypes) {
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

        $this->_publishable_modify($query, $params, $extra);
        $this->_searchable_modify($query, $params, $extra);
        $this->_tagable_modify($query, $params, $extra);

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
        return $this->fetch($query);
    }

    public function tags(array $params = array(), array $opts = array())
    {
        $query = $this->build($params + [
                'sort' => ["{$this->alias()}t.name"],
            ])
            ->resetDQLParts(['select', 'from', 'join'])
            ->select("
                {$this->alias()}t,
                COUNT({$this->alias()}) AS contentCount
            ")
            ->from("Chalk\Core\Tag", "{$this->alias()}t")
            ->innerJoin("{$this->alias()}t.contents", "{$this->alias()}", "WITH", "{$this->alias()} INSTANCE OF {$this->_entityName}")
            ->groupBy("{$this->alias()}t.id");

        $query = $this->prepare($query, [
            'hydrate' => \Chalk\Repository::HYDRATE_ARRAY,
        ] + $opts);
        return $this->fetch($query);
    }
}