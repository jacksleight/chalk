<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Frontend;

use Coast\UrlResolver as CoastUrlResolver;

class UrlResolver extends CoastUrlResolver
{
    public function __invoke()
    {
        $args = func_get_args();
        if (isset($args[0]) && is_numeric($args[0])) {
            $func = array($this, 'content');
        } else {
            $func = array('parent', '__invoke');
        }
        return call_user_func_array($func, $args);
    }

    public function content($content)
    {
        if (!is_numeric($content)) {
            $content = $content['id'];
        }
        $route = $this->router->has($content)
            ? $this->router->route($content)
            : null;
        $path = isset($route)
            ? $route['path']
            : "_c{$content}";
        return $this->string($path);
    }
}