<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class List extends \Js\Validator
{
	const INVALID = 'validator_list_invalid';

	protected $_properties = array('list');
	protected $_list = array();

	public function __construct(array $list = array())
	{
		$this->setList($list);
	}

	public function setList($list)
	{
		$this->_list = $list;
		return $this;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		if (!in_array($value, $this->_list)) {
			$this->_addError(self::INVALID);
			return false;
		}
		return true;
	}
}
