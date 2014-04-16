<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Dbal\Types;

use Doctrine\DBAL\Types,
	Doctrine\DBAL\Platforms\AbstractPlatform;

class CarbonDateTimeType extends Types\DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
    	$value = parent::convertToPHPValue($value, $platform);
        return isset($value)
        	? \Carbon\Carbon::instance($value)
        	: $value;
    }
}