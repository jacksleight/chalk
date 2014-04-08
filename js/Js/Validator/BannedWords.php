<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class BannedWords extends \Js\Validator
{
	const INVALID = 'validator_bannedWords_invalid';

	protected $_properties	= array('words');
	protected $_words		= array();

	public function __construct(array $words)
	{
		$this->setWords($words);
	}

	public function setWords(array $words)
	{
		foreach ($words as $word) {
			$this->addWord($word);
		}
		return $this;
	}

	public function addWord($word)
	{
		$this->_words[] = strtolower($word);
		return $this;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}
		
		foreach ($this->_words as $word) {
			if (strpos(strtolower($value), $word) !== false) {
				$this->_addError(self::INVALID);
				return false;
			}
		}
		return true;
	}
}
