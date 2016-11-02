<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Frontend;

use Chalk\App as Chalk;
use Closure;
use Coast\UrlResolver as CoastUrlResolver;
use Toast\Entity;

class UrlResolver extends CoastUrlResolver
{
    protected $_resolvers = [];

    public function __invoke()
    {
        $args = func_get_args();
        $isEntity = 
            (count($args) == 2 && is_string($args[0]) && is_numeric($args[1])) ||
            (count($args) == 1 && is_array($args[0]) && isset($args[0]['__CLASS__'])) ||
            (count($args) == 1 && $args[0] instanceof Entity);
        return $isEntity
            ? call_user_func_array([$this, 'entity'], $args)
            : call_user_func_array(['parent', '__invoke'], $args);
    }

    public function entity($entity, $id = null)
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
            if (!is_a($class, $resolver[0], true)) {
                continue;
            }
            $result = $resolver[1]($entity, $info);
            if (isset($result)) {
                if ($result) {
                    return $result;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    public function resolver($name, Closure $resolver)
    {
        $info = Chalk::info($name);
        $this->_resolvers = array_merge([[$info->class, $resolver]], $this->_resolvers);
        return $this;
    }
}