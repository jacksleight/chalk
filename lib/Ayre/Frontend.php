<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use DOMDocument,
    DOMXPath,
    Coast\App\Request,
    Coast\App\Response,
    Ayre\Core\Structure\Node;

class Frontend implements \Coast\App\Access, \Coast\App\Executable
{
    use \Coast\App\Access\Implementation;

    protected $_ayre;

    public function __construct(\Ayre $ayre)
    {
    	$this->_ayre = $ayre;
    }

    public function execute(Request $req, Response $res)
    {        
        $domain = $this->_ayre->em('Ayre\Core\Structure')->fetchFirst();
        $node   = $this->_ayre->em('Ayre\Core\Structure\Node')
            ->fetchByPath($domain, $req->path(), true);
        if (!$node) {
            return;
        }
        
        $req->node = $node;
        $method = \Ayre::type($node->content)->entity->var;
        return $this->$method($req, $res);
    }

    public function page(Request $req, Response $res)
    {
        $html = $this->view->render('index', [
            'req'  => $req,
            'res'  => $res,
            'node' => $req->node,
            'page' => $req->node->content
        ]);
       
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors(false);
        $xpath = new DOMXPath($dom);

        $els = $xpath->query('//*[@data-ayre]');
        foreach ($els as $el) {
            $data = json_decode($el->getAttribute('data-ayre'));
            if (isset($data->attrs)) {
                foreach ($data->attrs as $name => $callback) {
                    $el->setAttribute($name, '123');
                }
            }
            // $el->removeAttribute('data-ayre');
        }

        $html = $dom->saveHTML();


        return $res
            ->html($html);
    }

    public function file(Request $req, Response $res)
    {
        $file = $req->node->content->file();
        if (!$file->exists()) {
            return false;
        }
        return $res
            ->redirect($this->app->url->file($file));
    }

    public function url(Request $req, Response $res)
    {
        $url = $node->content->url();
        return $res
            ->redirect($url);       
    }

    public function route(Request $req, Response $res)
    {
        return;
    }
}