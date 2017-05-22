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