<?php
namespace Ayre\Entity\Structure;
	
class Iterator extends \RecursiveArrayIterator
{
	public function __construct($array = array(), $flags = 0)
	{
		if (!is_array($array)) {
			$array = $array->toArray();
		}
		parent::__construct($array, $flags);
	}

	public function hasChildren()
	{
		return isset($this->current()->children) && count($this->current()->children);
	}

	public function getChildren()
	{
		return new Iterator($this->current()->children);
	}
}
