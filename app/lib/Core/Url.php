<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core;

use Chalk\Core,
	Coast\Url as CoastUrl,
	Doctrine\Common\Collections\ArrayCollection;
use Coast\Validator;

/**
 * @Entity
*/
class Url extends Content
{
	public static $chalkSingular = 'External Link';
	public static $chalkPlural   = 'External Links';
	public static $chalkIsNode   = true;
	public static $chalkIsUrl    = true;

    /**
     * @Column(type="coast_url")
     */
	protected $url;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'mailtoEmailAddress' => array(
					'type'		=> 'string',
					'nullable'	=> true,
					'validator'	=> (new Validator)
						->emailAddress(),
				),
				'mailtoSubject' => array(
					'type'		=> 'string',
					'nullable'	=> true,
				),
				'mailtoBody' => array(
					'type'		=> 'text',
					'nullable'	=> true,
				),
			),
		);
	}

	public function url(CoastUrl $url = null)
	{
		if (isset($url)) {
			$this->url		= $url;
			$this->subtype	= $url->scheme();
			return $this;
		}
		return $this->url;
	}

	public function mailtoEmailAddress($emailAddress = null)
	{
		if (isset($emailAddress)) {
			$this->url = clone $this->url;
			$this->url->path($emailAddress);
			return $this;
		}
		return $this->url->path();
	}

	public function mailtoSubject($subject = null)
	{
		if (isset($subject)) {
			$this->url = clone $this->url;
			$this->url->queryParam('subject', $subject);
			return $this;
		}
		return $this->url->queryParam('subject');
	}

	public function mailtoBody($body = null)
	{
		if (isset($body)) {
			$this->url = clone $this->url;
			$this->url->queryParam('body', $body);
			return $this;
		}
		return $this->url->queryParam('body');
	}

	public static function staticSubtypeLabel($subtype)
	{	
		return $subtype == 'mailto'
			? 'Email'
			: strtoupper($subtype);
	}

	public function clarifier($context = false, $parts = [])
	{
		if ($this->url->scheme() == 'mailto') {
			$params = $this->url->queryParams();
			$parts = [$this->url->path()];
			if (isset($params['subject'])) {
				$parts[] = $params['subject'];
			}
		} else {
			$parts = [$this->url->toString()];
		}
		return parent::clarifier($context, $parts);
	}
	
	public function searchableContent()
	{
		return array_merge(parent::searchableContent(), [
			$this->url,
		]);
	}
}