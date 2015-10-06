<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Model\Url;

use Coast\Validator;
use Coast\Http;
use Coast\Url;
use DOMDocument;
use DOMXPath;

class Quick extends \Toast\Entity
{
	protected $url;

	protected $name;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'url' => array(
					'type'		=> 'coast_url',
				),
				'name' => array(
					'type'		=> 'string',
				),
			),
		);
	}

	public function url(Url $url = null)
	{
		if (func_num_args() > 0) {
			$this->url  = $url;
            if (isset($this->url)) {
                $this->name = $this->_resolveTitle($this->url);
            }
			return $this;
		}
		return $this->url;
	}

	protected function _resolveTitle(Url $url)
	{
        if (!$url->isHttp()) {
            return $url->toString();
        }
        $http = new Http(5);
        $res = $http->get($url);
        if (!$res->isSuccess()) {
            return $url->toString();
        }
        $url = $res->url();
        if (!preg_match('/^text\/html/i', $res->header('content-type'))) {
            return $url->toString();
        }
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $result = $doc->loadHTML($res->body());
        libxml_use_internal_errors(false);
        if (!$result) {
            return $url->toString();
        }
        $xpath = new DOMXPath($doc);
        $els = $xpath->query('//title');
        if (!$els->length) {
            return $url->toString();
        }
        return $els->item(0)->textContent;
	}
}