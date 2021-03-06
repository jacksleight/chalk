<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core;

use Chalk\Core;
use Coast\Url as CoastUrl;
use Doctrine\Common\Collections\ArrayCollection;
use Coast\Validator;

/**
 * @Entity
 * @Table(
 *     uniqueConstraints={@UniqueConstraint(columns={"url"})}
 * )
*/
class Url extends Content
{
	public static $chalkSingular = 'Link';
	public static $chalkPlural   = 'Links';
	public static $chalkIcon     = 'link';

    /**
     * @Column(type="coast_url")
     */
	protected $url;

    /**
     * @Column(type="coast_url", nullable=true)
     */
	protected $urlCanonical;

	protected static function _defineMetadata($class)
	{
		return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
			'fields' => array(
				'mailtoEmailAddress' => array(
					'type'		=> 'string',
					'nullable'	=> true,
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
		));
	}

	protected function _alterMailtoEmailAddressMetadata($meta)
	{
		if ($this->subtype != 'mailto') {
			return $meta;
		}

		$validator = new Validator();
		$validator
			->set()->break()
			->emailAddress();
		$validator->steps($meta['validator']->steps());

		$meta['nullable']  = false;
		$meta['validator'] = $validator;

		return $meta;
	}

	public function url($url = null)
	{
		if (isset($url)) {
			if ($url != $this->url) {
				try {
					$this->urlCanonical = $url->toCanonical();
				} catch (\Exception $e) {
					$this->urlCanonical = $url;
				}
			}
			$this->url = $url;
			if (isset($this->urlCanonical)) {
				$this->subtype = $this->urlCanonical->isHttp()
					? str_replace('www.', '', $this->urlCanonical->host())
					: strtoupper($this->urlCanonical->scheme());
			}
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
		return $subtype == 'MAILTO'
			? 'Email'
			: $subtype;
	}

	public function previewText($context = false)
	{
        $parts = parent::previewText($context);
		if ($this->url->scheme() == 'mailto') {
			$params   = $this->url->queryParams();
			$parts[$context ? 0 : 1] = $this->url->path();
			if (isset($params['subject'])) {
				$parts[] = $params['subject'];
			}
		} else {
			$parts[$context ? 0 : 1] = $this->url->toString();
		}
		return $parts;
	}
	
    public function searchContent(array $content = [])
    {
        return parent::searchContent(array_merge([
            $this->url,
        ], $content));
    }
}