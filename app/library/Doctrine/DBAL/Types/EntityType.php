<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Doctrine\DBAL\Types;

use Chalk\Chalk;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EntityType extends \Doctrine\DBAL\Types\JsonType
{
    const ENTITY = 'chalk_entity';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (isset($value)) {
            $value = json_decode($value, true) + [
                'type' => null,
                'id'   => null,
                'sub'  => null,
            ];
        }
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (isset($value)) {
            $value = json_encode($value + [
                'type' => null,
                'id'   => null,
                'sub'  => null,
            ]);
        }
        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function getName()
    {
        return self::ENTITY;
    }
}