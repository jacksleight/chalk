<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Parser\Plugin;

use Chalk\Chalk;
use Coast\App\Access;
use Chalk\Parser\Plugin;
use DOMDocument;
use DOMXPath;
use DOMText;

class Flatten extends Plugin implements Access
{
    use Access\Implementation;

    public function parse(DOMDocument $doc, DOMXPath $xpath)
    {
        $nodes = $xpath->query('//*[@data-chalk]');
        while ($nodes->length) {
            foreach ($nodes as $node) {
                $data = json_decode($node->getAttribute('data-chalk'), true);
                $node->removeAttribute('data-chalk');
                if (!$data) {
                    continue;
                }
                if (isset($data['widget'])) {
                    $html = '';
                    array_walk_recursive($data['widget']['params'], function($v) use (&$html) {
                        if (is_string($v) && preg_match('/(<\/.?>|\s)/is', $v) ) {
                            $html .= $v . ' ';
                        }
                    });
                    $temp  = $this->parser()->htmlToDoc($html);
                    $query = $temp->getElementsByTagName('body');
                    if ($query->length > 0) {
                        $temps = $query->item(0)->childNodes;
                        for ($i = 0; $i < $temps->length; $i++) {
                            $temp = $doc->importNode($temps->item($i), true);
                            $node->parentNode->insertBefore($temp, $node);
                        }
                    }
                    $node->parentNode->removeChild($node);
                }
            }
            $nodes = $xpath->query('//*[@data-chalk]');
        }
    }

    public function reverse(DOMDocument $doc, DOMXPath $xpath)
    {}
}