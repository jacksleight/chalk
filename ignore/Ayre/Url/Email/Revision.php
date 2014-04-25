<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Url\Email;

class Revision extends \Ayre\Url\Revision
{
	public function __construct(\Ayre\Locale $locale)
	{
		parent::__construct($locale);

		$this->url = new \JS\URL('mailto:');
	}

	protected static function _defineMetadata($class)
	{
		return array_merge_recursive(parent::_defineMetadata($class), array(
			'fields' => array(
				'emailAddress' => array(
					'type'		=> 'string',
					'persist'	=> false,
					'validator'	=> new \Toast\Validator\Chain(array(
						new \Toast\Validator\EmailAddress(),
					)),
				),
				'subject' => array(
					'type'		=> 'string',
					'persist'	=> false,
					'nullable'	=> true,
				),
				'body' => array(
					'type'		=> 'text',
					'persist'	=> false,
					'nullable'	=> true,
				),
			),
		));
	}
		
	public function setEmailAddress($emailAddress)
	{
		$this->url = clone $this->url;
		$this->url->setPath($emailAddress);
	}
		
	public function getEmailAddress()
	{
		return $this->url->getPath();
	}
		
	public function setSubject($subject)
	{
		$this->url = clone $this->url;
		$this->url->setQueryParam('subject', $subject);
	}
		
	public function getSubject()
	{
		return $this->url->getQueryParam('subject');
	}
		
	public function setBody($body)
	{
		$this->url = clone $this->url;
		$this->url->setQueryParam('body', $body);
	}
		
	public function getBody()
	{
		return $this->url->getQueryParam('body');
	}
}