<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Doctrine\DBAL\Types;

use Chalk\Chalk;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\SimpleArrayType;

class RefArrayType extends SimpleArrayType
{
    const REF_ARRAY = 'chalk_ref_array';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        if (isset($value)) {
            foreach ($value as $key => $ref) {
                $value[$key] = Chalk::ref($ref);
            }
        }
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (isset($value)) {
            foreach ($value as $key => $ref) {
                $value[$key] = Chalk::ref($ref, true);
            }
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function getName()
    {
        return self::REF_ARRAY;
    }
}