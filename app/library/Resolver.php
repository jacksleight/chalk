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
            (isset($args[0]) && !isset($args[1]) && is_array($args[0]) && isset($args[0]['__CLASS__'])) ||
            (isset($args[0]) && !isset($args[1]) && $args[0] instanceof Entity);
        return $isEntity
            ? call_user_func_array([$this, 'entity'], $args)
            : call_user_func_array(['parent', '__invoke'], $args);
    }

    public function entity($entity, $id = null, $data = false)
    {
        if (isset($id)) {
            $entity = [
                '__CLASS__' => Chalk::info($entity)->class,
                'id'        => $id,
            ];
        }
        if (is_object($entity)) {
            $class = get_class($entity);
        } else if (is_array($entity)) {
            $class = $entity['__CLASS__'];
        } else {
            throw new \Chalk\Exception('Unknown class');
        }
        $info = Chalk::info($class);
        foreach ($this->_resolvers as $resolver) {
            if (isset($resolver[0]) && !is_a($class, $resolver[0], true)) {
                continue;
            }
            $result = $resolver[1]($entity, $info);
            if ($result instanceof Url) {
                return $result;
            } else if (isset($result)) {
                if ($data) {
                    $result = $this->routeData($result['params'], $result['name'], true);
                } else {
                    $result = $this->route($result['params'], $result['name'], true);
                }
                if ($result) {
                    return $result;
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