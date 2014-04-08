<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class HtmlId extends \Js\Validator\Regex
{
	const INVALID = 'validator_htmlId_invalid';

	protected $_break = true;

	public function __construct()
	{
		parent::__construct('/^[^\s]+$/i');
	}
}