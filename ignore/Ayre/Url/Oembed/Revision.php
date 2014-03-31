<?php
/*
 * Copyright 2008-2013 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Ayre\Url\Oembed;

class Revision extends \Ayre\Url\Revision
{
	protected $data;
	
	protected static function _defineMetadata($class)
	{
		return array_merge_recursive(parent::_defineMetadata($class), array(
			'fields' => array(
				'data' => array(
					'type'		=> 'json',
				),
			),
		));
	}
		
	public function setUrl(\JS\URL $url = null)
	{
		if (isset($url) && !$url instanceof \JS\URL\Invalid && $url->isHttp()) {
			$this->data = null;
			$data = \Ayre::$instance->oembed->fetch($url);
			if ($data && $data['type'] != 'link') {
				$this->data = $data;
			}
		}
		$this->url = $url;
	}
	
	public function setData($data)
	{
		$this->data = $data;
		$this->item->subtype = $data['type'];
	}

	protected function _postValidate()
	{
		parent::_postValidate();

		if (!$this->hasErrors(array('url')) && !isset($this->data)) {
			$oembed = \Ayre::$instance->app->getConfig('oembed');
			$this->_addError('url', 'validator_oembed_invalid', array($oembed['name'], $oembed['url']));
		}
	}
}