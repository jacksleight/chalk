<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Entity\Exception;

class Validate extends \Exception
{
	protected $_entity;

	public function setEntity(\Js\Entity $entity)
	{
		$this->_entity = $entity;
		return $this;
	}

	public function getEntity()
	{
		return $this->_entity;
	}
}