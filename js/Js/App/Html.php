<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\App;

class Html
{
	public function style(array $props = null)
	{
		$lines = array();
		foreach ($props as $name => $value) {
			$lines[] = "{$name}: {$value};";
		}
		return implode(' ', $lines);
	}
	
	public function ratio($width, $height)
	{
		return $this->style(array(
			'padding-top'	=> (($height / $width) * 100) . "%",
		));
	}

	public function ratioFluid($smallWidth, $smallHeight, $largeWidth, $largeHeight)
	{
		$slope = ($largeHeight - $smallHeight) / ($largeWidth - $smallWidth);
		return $this->style(array(
			'padding-top'	=> ($slope * 100) . "%",
			'height'		=> ($smallHeight - $smallWidth * $slope) . 'px'
		));
	}
}