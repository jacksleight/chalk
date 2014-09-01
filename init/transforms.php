<?php
return [
    'resize' => function($image, $params) {
        $this->run('orientate', $image, $params);
        
        $params = $params + [
            'size'   => null,
            'width'  => null,
            'height' => null,
            'crop'   => false,
        ];

        $width  = isset($params['size']) ? $params['size'] : $params['width'];
        $height = isset($params['size']) ? $params['size'] : $params['height'];

        if ($params['crop'] && isset($width) && isset($height)) {
            $image->fit($width, $height, function($constraint) {
                $constraint->upsize();
            });
        } else {
            $image->resize($width, $height, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
    },
    'orientate' => function($image, $params) {
        if ($image->mime != 'image/jpeg' || !extension_loaded('exif')) {
            return;
        }
        $image->orientate();
    }
];