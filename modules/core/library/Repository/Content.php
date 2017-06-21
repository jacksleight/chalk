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
use Chalk\Core\Behaviour\Trackable;
use DateTime;

class Content extends Repository
{
    use Publishable\Repository;
    use Searchable\Repository;
    use Tagable\Repository;
    use Trackable\Repository;

    protected $_sort = ['name', 'ASC'];

    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

        $params = $params + [
            'filtersInfo' => null,
            'types'       => null,
            'subtypes'    => null,
            'nodes'       => null,
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

        if (isset($params['nodes']) && count($params['nodes'])) {
            $query
                ->addSelect("n")
                ->leftJoin("{$this->alias()}.nodes", "n")
                ->andWhere(":nodes MEMBER OF {$this->alias()}.nodes")
                ->setParameter('nodes', $params['nodes']);
        }

        $this->_publishable_modify($query, $params, $extra);
        $this->_searchable_modify($query, $params, $extra);
        $this->_tagable_modify($query, $params, $extra);
        $this->_trackable_modify($query, $params, $extra);

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