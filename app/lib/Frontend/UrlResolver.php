<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Frontend;

use Chalk\Chalk;
use Chalk\Core\entity;
use Closure;
use Coast\UrlResolver as CoastUrlResolver;

class UrlResolver extends CoastUrlResolver
{
    protected $_resolvers = [];

    public function __invoke()
    {
        $args = func_get_args();
        if (!isset($args[0])) {
            $func = array($this, 'string');
        } else {
            $func = array($this, 'entity');
        }
        return call_user_func_array($func, $args);
    }

    public function entity($entity)
    {
        if (is_object($entity)) {
            $class = get_class($entity);
        } else if (is_array($entity)) {
            
            var_dump($entity);
            die;
            
        }



 
        foreach ($this->_resolvers as $resolver) {
            if (!is_a($class, $resolver[0], true)) {
                continue;
            }
            $result = $resolver[1]($entity);
            if (isset($result)) {
                if ($result) {
                    return $result;
                } else {
                    return false;
                }
            }
        }
    }

    public function resolver($name, Closure $resolver)
    {
        $info = Chalk::info($name);
        $this->_resolvers = array_merge([[$info->class, $resolver->bindTo($this)]], $this->_resolvers);
        return $this;
    }
}