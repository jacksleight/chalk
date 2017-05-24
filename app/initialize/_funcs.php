<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk;

function is_image(\Coast\File $file)
{
    $exts = [
        'gif',
        'jpg',
        'jpeg',
        'png',
        'webp',
    ];
    return in_array($file->extName(), $exts);
}

function str_slugify($string)
{
    return strtolower(\Coast\str_slugify(iconv('utf-8', 'ascii//translit//ignore', $string)));
}

function filters_list_build($filters)
{
    if (!isset($filters)) {
        return null;
    } else if (!is_array($filters)) {
        return "~{$filters}";
    } else {
        $filtersList = [];
        foreach ($filters as $name => $split) {
            $filtersList[] = implode('~', array_merge([$name], $split));
        }
        return implode('~~', $filtersList);
    }
}

function filters_list_parse($filtersList)
{
    if (isset($filtersList)) {
        if (substr($filtersList, 0, 1) == '~') {
            return substr($filtersList, 1);
        }
        $parts = explode('~~', $filtersList);
        $filters = [];
        foreach ($parts as $part) {
            $split = explode('~', $part);
            $name  = array_shift($split);
            $filters[$name] = $split;
        }
        return $filters;
    } else {
        return [];
    }
}