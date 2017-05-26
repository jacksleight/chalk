<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Doctrine\DBAL\Types;

use Chalk\Chalk;

class EntityType extends \Doctrine\DBAL\Types\Type
{
    const ENTITY = 'chalk_entity';

    public function getSqlDeclaration(array $fieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        if (isset($value)) {
            $value = json_decode($value);
            $value = \Toast\Wrapper::$em->ref($value->type, $value->id);
        }
        return $value;
    }

    public function convertToDatabaseValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        if (isset($value)) {
            $info  = Chalk::info($value);
            $value = json_encode(['type' => $info->name, 'id' => $value->id]);
        }
        return $value;
    }

    public function getName()
    {
        return self::ENTITY;
    }

    public function requiresSQLCommentHint(\Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return true;
    }
}