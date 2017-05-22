<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository,
    Chalk\Core\Behaviour\Searchable;

class Tag extends Repository
{
    use Searchable\Repository;
    
    protected $_sort = ['name', 'ASC'];

    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

        $this->_searchable_modify($query, $params);

        return $query;
    }

    public function names(array $names)
    {
        $map = [];
        foreach ($names as $name) {
            $slug = \Chalk\str_slugify($name);
            $map[$slug] = trim($name);
        }
        $tags = $this->all(['slugs' => array_keys($map)]);
        foreach ($tags as $tag) {
            unset($map[$tag->slug]);
        }
        foreach ($map as $slug => $name) {
            $tags[] = $this->create([
                'name' => $name,
            ]);
        }
        return $tags;
    }
}