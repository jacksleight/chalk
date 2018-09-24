<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

namespace Chalk;

use Chalk\Chalk;
use Chalk\Entity;
use Closure;
use Coast\Url;
use Coast\Resolver as CoastResolver;

class Resolver extends CoastResolver
{
    protected $_resolvers = [];

    public function __invoke()
    {
        $args = func_get_args();
        $isEntity =
            (isset($args[1]) && is_string($args[0]) && is_numeric($args[1])) ||
            (isset($args[0]) && is_array($args[0]) && isset($args[0]['__CLASS__'])) ||
            (isset($args[0]) && $args[0] instanceof Entity);
        return $isEntity
            ? call_user_func_array([$this, 'entity'], $args)
            : call_user_func_array(['parent', '__invoke'], $args);
    }

    public function entity($type, $id = null, $sub = null, $data = false)
    {
        $info = Chalk::info($type);
        if (!is_string($type)) {
            $entity = $type;
            $data   = $sub;
            $sub    = $id;
        } else {
            $entity = [
                '__CLASS__' => $info->class,
                'id'        => $id,
            ];
        }
        if (isset($sub)) {
            $sub = Chalk::sub($sub);
        }
        foreach ($this->_resolvers as $resolver) {
            if (isset($resolver[0]) && !is_a($info->class, $resolver[0], true)) {
                continue;
            }
            $url = $resolver[1]($entity, $sub, $info);
            if ($url instanceof Url) {
                if (isset($sub) && $sub['type'] == 'fragment') {
                    $url->fragment($sub['id']);
                }
                return $url;
            } else if (isset($url)) {
                $method = $data ? 'routeData' : 'route';
                $url = $this->{$method}($url['params'], $url['name'], true);
                if ($url) {
                    return $url;
                }
            }
        }
        return false;
    }

    public function resolver($name, Closure $resolver)
    {
        $class = isset($name)
            ? Chalk::info($name)->class
            : null;
        $this->_resolvers = array_merge([[$class, $resolver]], $this->_resolvers);
        return $this;
    }
}