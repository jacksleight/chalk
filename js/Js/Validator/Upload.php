<?php
/* 
 * Copyright 2008-2014 Jack Sleight <http://jacksleight.com/>
 * Any redistribution or reproduction of part or all of the contents in any form is prohibited.
 */

namespace Js\Validator;

class Upload extends \Js\Validator
{
	const CONNECTION_ERROR	= 'validator_upload_connectionError';
	const SERVER_ERROR		= 'validator_upload_serverError';
	const TOO_BIG			= 'validator_upload_tooBig';
	const TOO_BIG_SERVER	= 'validator_upload_tooBigServer';
	const INVALID_MIME_TYPE	= 'validator_upload_invalidMimeType';

	protected $_break = true;

	protected $_properties = array('mimeTypes', 'maxSize');
    protected $_mimeTypes;
    protected $_maxSize;

    public function __construct(array $mimeTypes = null, $maxSize = null)
    {
		$this->setMimeTypes($mimeTypes);
		$this->setMaxSize($maxSize);
    }

	public function setMimeTypes(array $mimeTypes)
	{
		$this->_mimeTypes = $mimeTypes;
		return $this;
	}

	public function getMimeTypes()
	{
		return $this->_mimeTypes;
	}

	public function setMaxSize($maxSize)
	{
		$sizes = array(
			\Js\str_to_bytes(ini_get('post_max_size')),
			\Js\str_to_bytes(ini_get('upload_max_filesize')),
		);
		$memoryLimit = ini_get('memory_limit');
		if ($memoryLimit != -1) {
			$sizes[] = \Js\str_to_bytes($memoryLimit);
		}
		if (isset($maxSize)) {
			$sizes[] = $maxSize;
		}

		$this->_maxSize = min($sizes);
		return $this;
	}

	public function getMaxSize()
	{
		return $this->_maxSize;
	}

	public function isValid($value)
	{
		$this->_resetErrors();
		if (!isset($value)) {
			return true;
		}

		if ($value['error'] != UPLOAD_ERR_OK) {
			switch ($value['error']) {
				case UPLOAD_ERR_PARTIAL:
					$this->_addError(self::CONNECTION_ERROR);
					return false;
				break;
				case UPLOAD_ERR_FORM_SIZE:
					$this->_addError(self::TOO_BIG);
					return false;
				break;
				case UPLOAD_ERR_INI_SIZE:
					$this->_addError(self::TOO_BIG_SERVER);
					return false;
				break;
				default:
					$this->_addError(self::SERVER_ERROR);
					return false;
				break;
			}
		}
		if (isset($this->_mimeTypes) && !in_array($value['type'], $this->_mimeTypes)) {
			$this->_addError(self::INVALID_MIME_TYPE);
			return false;
		}
		if (isset($this->_maxSize) && $value['size'] > $this->_maxSize) {
			$this->_addError(self::TOO_BIG);
			return false;
		}
		return true;
	}
}