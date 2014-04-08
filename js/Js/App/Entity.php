<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\App;

class Entity implements \Coast\App\Access
{
    use \Coast\App\Access\Implementation;

	public function wrap($object, $allowed = null, array $md = null)
	{
		if ($object instanceof \Js\Entity) {
			return new \Js\Entity\Wrapper\Entity($object, $allowed);
		} elseif ($object instanceof \Doctrine\Common\Collections\Collection) {
			return new \Js\Entity\Wrapper\Collection($object, $allowed, null, null, $md);
		} else {
			return $object;
		}
	}
}