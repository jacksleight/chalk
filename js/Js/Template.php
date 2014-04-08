<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js;

class Template
{
	protected $_params;

	public function render($content, $params = array())
	{
		$this->_params = $params;

		$content = preg_replace_callback('/\{(\w+)\}/is', array($this, '_echo'), $content);
		$content = preg_replace_callback('/\{if:(\w+)([=!<>]+)(.*?)\}(.*?)\{\/if\}/is', array($this, '_if'), $content);
		$content = preg_replace('/(\r?\n){2,}/', "\n\n", $content);

		$this->_params = null;
		return $content;
	}

	protected function _echo($match)
	{
		if (!isset($this->_params[$match[1]])) {
			return $match[0];
		}

		return $this->_params[$match[1]];
	}

	protected function _if($match)
	{
		if (!isset($this->_params[$match[1]])) {
			return $match[0];
		}

		$o = $match[2];
		$a = $this->_params[$match[1]];
		$b = $match[3];
		$t = ($o == '='  && $a == $b)
		  || ($o == '!=' && $a != $b)
		  || ($o == '>'  && $a >  $b)
		  || ($o == '>=' && $a >= $b)
		  || ($o == '<'  && $a <  $b)
		  || ($o == '<=' && $a <= $b);

		return $t ? $match[4] : null;
	}
}