<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Hostname extends \Js\Validator\Regex
{
	const INVALID = 'validator_hostname_invalid';

	protected $_break = true;

	public function __construct()
	{
		parent::__construct('/^[a-z0-9-\.]+$/i');
	}
}