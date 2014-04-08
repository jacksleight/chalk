<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js;

class Locale
{
	const DATETIME_NONE		= \IntlDateFormatter::NONE;
	const DATETIME_SHORT	= \IntlDateFormatter::SHORT;
	const DATETIME_MEDIUM	= \IntlDateFormatter::MEDIUM;
	const DATETIME_LONG		= \IntlDateFormatter::LONG;
	const DATETIME_FULL		= \IntlDateFormatter::FULL;
	
	protected $_name;
	protected $_currency;
	protected $_timezone;
	
	protected $_file;
	protected $_messages;

	static public function acceptFromHttp($header)
	{
		return \Locale::acceptFromHttp($header);
	}

	static public function lookup($tags, $locale, $default)
	{
		return \Locale::lookup($tags, $locale, true, $default);
	}
	
	public function __construct($name, \Coast\File $file = null)
	{
		$this->_name = \Locale::canonicalize($name);
		$keywords = \Locale::getKeywords($this->_name);
		$this->_currency = isset($keywords['currency'])
			? $keywords['currency']
			: null;
		$this->_timezone = isset($keywords['timezone'])
			? $keywords['timezone']
			: null;
		
		$this->_file = $file;
		if (isset($this->_file)) {
			require $this->_file->toString();
			$this->_messages = $messages;
		}
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getLanguage()
	{
		return \Locale::getPrimaryLanguage($this->_name);
	}

	public function getScript()
	{
		return \Locale::getScript($this->_name);
	}

	public function getRegion()
	{
		return \Locale::getRegion($this->_name);
	}

	public function getVariants()
	{
		return \Locale::getAllVariants($this->_name);
	}

	public function getTimezone()
	{
		return $this->_timezone;
	}

	public function getCurrency()
	{
		return $this->_currency;
	}

	public function getDisplayName(\JS\Locale $locale = null)
	{
		$locale = isset($locale)
			? $locale->getName()
			: $this->_name;
		return \Locale::getDisplayName($this->_name, $locale);
	}

	public function getDisplayLanguage(\JS\Locale $locale = null)
	{
		$locale = isset($locale)
			? $locale->getName()
			: $this->_name;
		return \Locale::getDisplayLanguage($this->_name, $locale);
	}

	public function getDisplayScript(\JS\Locale $locale = null)
	{
		$locale = isset($locale)
			? $locale->getName()
			: $this->_name;
		return \Locale::getDisplayScript($this->_name, $locale);
	}

	public function getDisplayRegion(\JS\Locale $locale = null)
	{
		$locale = isset($locale)
			? $locale->getName()
			: $this->_name;
		return \Locale::getDisplayRegion($this->_name, $locale);
	}

	public function getDisplayVariant(\JS\Locale $locale = null)
	{
		$locale = isset($locale)
			? $locale->getName()
			: $this->_name;
		return \Locale::getDisplayVariant($this->_name, $locale);
	}

	public function getDisplayTimezone(\JS\Locale $locale = null)
	{
		if (!isset($this->_timezone)) {
			return;
		}
		return $this->_getDisplayKeyword('Time Zone', $locale);
	}

	public function getDisplayCurrency(\JS\Locale $locale = null)
	{
		if (!isset($this->_currency)) {
			return;
		}
		return $this->_getDisplayKeyword('Currency', $locale);
	}

	protected function _getDisplayKeyword($name, \JS\Locale $locale = null)
	{
		$keys	= $this->_parseDisplayName($this->getDisplayName('en'));
		$values	= $this->_parseDisplayName($this->getDisplayName($locale));
		$words	= array_combine(array_keys($keys), array_values($values));		
		return isset($words[$name])
			? $words[$name]
			: null;
	}

	protected function _parseDisplayName($name)
	{
		preg_match('/\((.*?)\)/', $name, $match);
		$parts = explode(',', $match[1]);
		$words = array();
		foreach ($parts as $i => $part) {
			if ($i == 0) {
				$part = 'Region=' . $part;
			}
			list($name, $value) = explode('=', trim($part));
			$words[$name] = $value;
		}
		return $words;	
	}
	
	public function number($value)
	{
		$formatter = new \NumberFormatter($this->_name, \NumberFormatter::DECIMAL);
		return $formatter->format($value);
	}
	
	public function currency($value, $decimals = true)
	{
		$formatter = new \NumberFormatter($this->_name, \NumberFormatter::CURRENCY);
		$value = $formatter->formatCurrency($value, $this->_currency);
		if (!$decimals) {
			$symbol = $formatter->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL);
			$value  = preg_replace('/' . preg_quote($symbol) . '[0-9]*/', '', $value);
		}
		return $value;		
	}

	public function percent($value)
	{
		$formatter = new \NumberFormatter($this->_name, \NumberFormatter::PERCENT);
		return $formatter->format($value);
	}

	public function scientific($value)
	{
		$formatter = new \NumberFormatter($this->_name, \NumberFormatter::SCIENTIFIC);
		return $formatter->format($value);
	}

	public function spellout($value)
	{
		$formatter = new \NumberFormatter($this->_name, \NumberFormatter::SPELLOUT);
		return $formatter->format($value);
	}

	public function ordinal($value)
	{
		$formatter = new \NumberFormatter($this->_name, \NumberFormatter::ORDINAL);
		return $formatter->format($value);
	}

	public function duration($value, $words = false)
	{
		$formatter = new \NumberFormatter($this->_name, \NumberFormatter::DURATION);
		if ($words) {
			$formatter->setTextAttribute(\NumberFormatter::DEFAULT_RULESET, '%with-words');
		}
		return $formatter->format($value);
	}
	
	public function date(\DateTime $dateTime, $dateType = self::DATETIME_FULL, $timezone = null)
	{
		return $this->dateTime($dateTime, $dateType, self::DATETIME_NONE, $timezone);
	}
	
	public function time(\DateTime $dateTime, $timeType = self::DATETIME_FULL, $timezone = null)
	{
		return $this->dateTime($dateTime, self::DATETIME_NONE, $timeType, $timezone);
	}
	
	public function dateTime(\DateTime $dateTime, $dateType = self::DATETIME_FULL, $timeType = self::DATETIME_FULL)
	{
		$formatter = new \IntlDateFormatter($this->_name, $dateType, $timeType, $this->_timezone);
		return $formatter->format($dateTime->getTimestamp());
	}
	
	public function message($name, array $params = null, $default = null)
	{
		$params = isset($params)
			 ? $params
			 : array();
		$parts = explode('_', $name);
		$messages = &$this->_messages;
		foreach ($parts as $i => $part) {
			if ($this->_isShim($messages)) {
				$messages = &$messages[1];
			}
			if (!isset($messages[$part])) {
				break;
			}
			$messages = &$messages[$part];
		}
		if (is_array($messages)) {
			if ($this->_isShim($messages)) {
				$messages = &$messages[0];
			} else {
				return isset($default)
					? $default
					: $name;
			}
		}
		$formatter = new \MessageFormatter($this->_name, $messages);
		if (!isset($formatter)) {
			return isset($default)
				? $default
				: $name;
		}
		return $formatter->format($params);
	}

	public function messagePlain($name, array $params = null, $default = null)
	{
		return strip_tags($this->message($name, $params, $default));
	}
	
	protected function _isShim($array)
	{
		return is_array($array)
			&& count($array) == 2
			&& isset($array[0])
			&& isset($array[1])
			&& is_string($array[0])
			&& is_array($array[1]);
	}
}