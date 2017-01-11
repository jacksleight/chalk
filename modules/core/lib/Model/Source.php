<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Model;

use Chalk\Parser;
use Toast\Wrapper;

class Source extends \Toast\Entity
{
	protected $lang;

	protected $code;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'lang' => array(
					'type'	=> 'text',
				),
				'code' => array(
					'type'	=> 'text',
				),
			),
		);
	}

	public function code($value = null)
	{
		if (func_num_args() > 0) {
			if ($this->lang == 'json') {
				$value = json_encode(json_decode($value, true), JSON_PRETTY_PRINT);
			}
			$this->code = $value;
			return $this;
		}
		return $this->code;
	}

	public function codeRaw()
	{
		$value = $this->code;
		if ($this->lang == 'html') {
			$value = Wrapper::$chalk->backend->parser->parse($value);
		} else if ($this->lang == 'json') {
			$value = json_encode(json_decode($value, true));
		}
		return $value;
	}
}