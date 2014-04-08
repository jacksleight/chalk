<?php
/*
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js;

class Cli
{
	protected $_args = array();
	protected $_depth = 0;

	public function __construct(array $args = array())
	{
		$this->_args = array_slice($args, 1);
	}

	public function execute($command)
	{
		$output = array();
		exec($command, $output);
		return $output;
	}

	public function getArgs()
	{
		return $this->_args;
	}

	public function getArg($index)
	{
		return $this->_args[$index];
	}

	public function depthUp()
	{
		return $this->_depth++;
	}

	public function depthDown()
	{
		return $this->_depth--;
	}

	public function prompt($text, $depth = 0, $type = 'string')
	{
		fwrite(STDOUT, str_repeat('  ', $depth + $this->_depth) . $text . '? ');
		$value = trim(fgets(STDIN));
		if ($value == 'x') {
			exit(0);
		} elseif (strlen($value) == 0) {
			$value = null;
		} else {
			switch ($type) {
				case 'int':
					$value = (int) $value;
				break;
				case 'bool':
					$value = $value == 'y';
				break;
			}
		}
		return $value;
	}

	public function data($data, $depth = 0)
	{
		$lines = explode("\n", print_r($data, true));
		foreach ($lines as $i => $line) {
			$line = trim($line);
			if (strlen($line) == 0 || $line == '(' || $line == ')') {
				unset($lines[$i]);
				continue;
			}
			$lines[$i] = str_replace('        ', '    ', $lines[$i]);
			$lines[$i] = str_replace('    ', '  ', $lines[$i]);
			$lines[$i] = str_repeat('  ', $depth + $this->_depth) . $lines[$i];
		}
		echo implode("\n", $lines) . "\n";
		return $this;
	}

	public function message($text, $depth = 0)
	{
		echo str_repeat('  ', $depth + $this->_depth) . $text . "\n";
		return $this;
	}

	public function status($text, $depth = 0)
	{
		echo str_repeat('  ', $depth + $this->_depth) . $text . ".. ";
		return $this;
	}

	public function ok()
	{
		echo "OK\n";
		return $this;
	}

	public function skipped($text = null, $depth = 0)
	{
		echo "SKIPPED";
		if (isset($text)) {
			echo ":\n" . str_repeat('  ', $depth + $this->_depth + 1) . $text .  "\n";
		} else {
			echo "\n";
		}
		return $this;
	}

	public function warning($text = null, $depth = 0)
	{
		echo "WARNING";
		if (isset($text)) {
			echo ":\n" . str_repeat('  ', $depth + $this->_depth + 1) . $text .  "\n";
		} else {
			echo "\n";
		}
		return $this;
	}

	public function error($text = null, $depth = 0)
	{
		echo "ERROR";
		if (isset($text)) {
			echo ":\n" . str_repeat('  ', $depth + $this->_depth + 1) . $text .  "\n";
		} else {
			echo "\n";
		}
		return $this;
	}
}