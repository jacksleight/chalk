<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Coast\Image;

$actions = [
    'default' => function($image, $params) {
        $this->run('orientate', $image, $params);
        $this->run('resize', $image, $params);
        $this->run('compress', $image, $params);
    },
    'orientate' => function($image, $params) {
        if ($image->mime != 'image/jpeg' || !extension_loaded('exif')) {
            return;
        }
        $image->orientate();
    },
    'resize' => function($image, $params) {
        $params = $params + [
            'size'   => null,
            'width'  => null,
            'height' => null,
            'crop'   => false,
        ];
        if (isset($params['size'])) {
            $params['width']  = $params['size'];
            $params['height'] = $params['size'];
        }
        if ($params['crop'] && isset($params['width']) && isset($params['height'])) {
            $image->fit($params['width'], $params['height'], function($constraint) {
                $constraint->upsize();
            });
        } else {
            $image->resize($params['width'], $params['height'], function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
    },
    'compress' => function($image, $params) {
        $params = $params + [
            'quality' => null,
        ];
        if (isset($params['quality'])) {
            $image->quality = $params['quality'];
        }
    }
];

return new Image([
    'prefix'         => 'image',
    'baseDir'        => $app->chalk->config->publicDataDir->dir('file'),
    'outputDir'      => $app->chalk->config->publicDataDir->dir('image'),
    'resolver'       => $app->resolver,
    'outputResolver' => $app->frontend->resolver,
    'actions'        => $actions,
]);