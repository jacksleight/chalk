<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Frontend;

use Coast\App\UrlResolver as CoastUrlResolver;

class UrlResolver extends CoastUrlResolver
{
    public function __invoke()
    {
        $args = func_get_args();
        if (is_integer($args[0])) {
            $func = array($this, 'content');
        } else {
            $func = array('parent', '__invoke');
        }
        return call_user_func_array($func, $args);
    }

    public function content($content)
    {
        if ($content instanceof Content) {
            $content = $content->id;
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