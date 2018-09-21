<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Doctrine\DBAL\Types;

use Chalk\Chalk;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class RefType extends \Doctrine\DBAL\Types\JsonType
{
    const REF = 'chalk_ref';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        if (isset($value)) {
            $value = Chalk::ref($value);
        }
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (isset($value)) {
            $value = Chalk::ref($value);
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function getName()
    {
        return self::REF;
    }
}