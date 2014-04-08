<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Doctrine\Orm\Mapping\Driver;

class StaticPhpDriver extends \Doctrine\ORM\Mapping\Driver\StaticPHPDriver
{
    public function isTransient($className)
    {
		$reflection = new \ReflectionClass($className);
		return $reflection->isAbstract() || !method_exists($className, 'loadMetadata');
    }
}