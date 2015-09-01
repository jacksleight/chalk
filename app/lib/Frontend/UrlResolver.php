<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Frontend;

use Chalk\Chalk;
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
            $func = array($this, 'content');
        }
        return call_user_func_array($func, $args);
    }

    public function content($content)
    {
        $info = Chalk::info($content);
        if (!isset($this->_resolvers[$info->name])) {
            return false;
        }        
        foreach ($this->_resolvers[$info->name] as $resolver) {
            $result = $resolver($content);
            if (isset($result)) {
                if ($result) {
                    return $result;
                } else {
                    return false;
                }
            }
        }
    }

    public function resolver($entity, Closure $resolver)
    {
        $this->_resolvers[$entity][] = $resolver->bindTo($this);
        return $this;
    }
}