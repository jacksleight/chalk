<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

use Coast\App\Request,
    Coast\App\Response,
    Ayre\Entity\Structure\Node;

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
        $domain = $this->_ayre->em('Ayre\Entity\Domain')->fetch(3);
        $node   = $this->_ayre->em('Ayre\Entity\Structure\Node')
            ->fetchByPath($domain, $req->path(), true);
        if (!$node) {
            return;
        }
        
        $method = \Ayre::type($node->content)->entity->method;
        return $this->$method($node, $req, $res);
    }

    public function page(Node $node, Request $req, Response $res)
    {
        return $res
            ->html($this->view->render('index', [
                'req'  => $req,
                'res'  => $res,
                'node' => $node,
                'page' => $node->content->last
            ]));
    }

    public function file(Node $node, Request $req, Response $res)
    {
        $file = $node->content->last->file();
        if (!$file->exists()) {
            return false;
        }
        return $res
            ->redirect($this->app->url($file));
    }

    public function url(Node $node, Request $req, Response $res)
    {
        $url = $node->content->last->url();
        return $res
            ->redirect($url);       
    }
}