<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Dbal\Types;

class UrlType extends \Doctrine\DBAL\Types\Type
{
	const URL = 'url';

	public function getSqlDeclaration(array $fieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
	}

	public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return isset($value)
			? new \Coast\Url($value)
			: null;
	}

	public function convertToDatabaseValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return isset($value)
			? $value->toString()
			: null;
	}

	public function getName()
	{
		return self::URL;
	}
}