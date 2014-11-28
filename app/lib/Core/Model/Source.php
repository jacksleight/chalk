<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Model;

use Respect\Validation\Validator,
    DOMDocument,
    DOMXPath;

class Source extends \Toast\Entity
{
	protected $lang;

	protected $code;

	protected static function _defineMetadata($class)
	{
		return array(
			'fields' => array(
				'lang' => array(
					'type'	=> 'text',
				),
				'code' => array(
					'type'	=> 'text',
				),
			),
		);
	}

	public function code($value = null)
	{
		if (func_num_args() > 0) {
			if ($this->lang == 'html') {
				$value = $this->_tidyHtml($value, true);
			} else if ($this->lang == 'json') {
				$value = json_encode(json_decode($value, true), JSON_PRETTY_PRINT);
			}
			$this->code = $value;
			return $this;
		}
		return $this->code;
	}

	public function codeRaw()
	{
		$value = $this->code;
		if ($this->lang == 'json') {
			$value = json_encode(json_decode($value, true));
		}
		return $value;
	}

    protected function _tidyHtml($value)
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        // @hack Ensures correct encoding as libxml doesn't understand <meta charset="utf-8">
        $doc->loadHTML('<?xml encoding="utf-8">' . $value);
        libxml_use_internal_errors(false);
        foreach ($doc->childNodes as $node) {
            if ($node->nodeType == XML_PI_NODE) {
                $doc->removeChild($node);
                break;
            }
        }

        $doc->normalizeDocument();
		foreach ((new DOMXPath($doc))->query('.//text()') as $node) {
			if ($node->isWhitespaceInElementContent() && $node->parentNode) {
				$node->parentNode->removeChild($node);
			}
		}

		$frag = new DOMDocument(null, 'utf-8');
		foreach ($doc->getElementsByTagName('body')->item(0)->childNodes as $child) {
			$frag->appendChild($frag->importNode($child, true));
		}

		$frag->preserveWhiteSpace = false;
		$frag->formatOutput = true;

		$value = $frag->saveXML($frag, LIBXML_NOEMPTYTAG);
		$value = preg_replace('/<\?xml.*?\?>\n/u', '', $value);
		$value = preg_replace('/<!\[CDATA\[(.*?)\]\]>/us', '$1', $value);
		$value = preg_replace('/<\/(area|base|basefont|br|col|frame|hr|img|input|isindex|link|meta|param)>/u', '', $value);
		$value = preg_replace('/\n(\s+)/u', "\n$1$1", $value);
		$value = trim($value);
		return $value;
    }
}