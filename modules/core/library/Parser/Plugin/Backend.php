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

class Backend implements Plugin, Access
{
    use Access\Implementation;

    public function parse(DOMDocument $doc, DOMXPath $xpath)
    {
        $nodes = $xpath->query('//*[@data-chalk]');
        foreach ($nodes as $node) {
            $data = json_decode($node->getAttribute('data-chalk'), true);
            if (!$data) {
                continue;
            }
            if (isset($data['widget'])) {
                while ($node->hasChildNodes()) {
                    $node->removeChild($node->firstChild);
                }
                $info   = Chalk::info($data['widget']['name']);
                $class  = $info->class;
                $widget = $this->em->wrap(new $class())->graphFromArray($data['widget']['params']);
                $html   = $this->view->render('widget/card', ['widget' => $widget->getObject()], 'core');
                $temp   = $this->parser->htmlToDoc($html);
                $temps  = $temp->getElementsByTagName('body')->item(0)->childNodes;
                for ($i = 0; $i < $temps->length; $i++) {
                    $temp = $doc->importNode($temps->item($i), true);
                    $node->appendChild($temp);
                }
                $this->_addClass($node, 'mceNonEditable');
            }
        }
    }

    public function reverse(DOMDocument $doc, DOMXPath $xpath)
    {
        $nodes = $xpath->query('//*[@data-chalk]');
        foreach ($nodes as $node) {
            $data = json_decode($node->getAttribute('data-chalk'), true);
            if (!$data) {
                continue;
            }
            if (isset($data['widget'])) {
                while ($node->hasChildNodes()) {
                    $node->removeChild($node->firstChild);
                }
                $this->_removeClass($node, 'mceNonEditable');
            }
        }
    }

    protected function _addClass($node, $class)
    {
        $value = $node->getAttribute('class');
        $value = trim($value);
        $classes = $value
            ? preg_split('/\s+/', $value)
            : [];
        if (!in_array($class, $classes)) {
            $classes[] = $class;
        }
        $value = implode(' ', $classes);
        $node->setAttribute('class', $value);
    }

    protected function _removeClass($node, $class)
    {
        $value = $node->getAttribute('class');
        $value = trim($value);
        $classes = $value
            ? preg_split('/\s+/', $value)
            : [];
        while (false !== $i = array_search($class, $classes)) {
            unset($classes[$i]);
        }
        if (!count($classes)) {
            $node->removeAttribute('class');
        } else {
            $value = implode(' ', $classes);
            $node->setAttribute('class', $value);
        }
    }
}