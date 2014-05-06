<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Ayre;

class Frontend implements \Coast\App\Access, \Coast\App\Executable
{
    use \Coast\App\Access\Implementation;

    protected $_ayre;

    public function __construct(\Ayre $ayre)
    {
    	$this->_ayre = $ayre;
    }

    public function execute(\Coast\App\Request $req, \Coast\App\Response $res)
    {        
        $domain = $this->_ayre->em('Ayre\Entity\Domain')->fetch(3);
        $node   = $this->_ayre->em('Ayre\Entity\Structure\Node')
            ->fetchByPath($domain, $req->path(), true);
        if (!$node) {
            return;
        }
        return $res
			->html($this->view->render('index', [
                'req'  => $req,
                'res'  => $res,
                'node' => $node,
                'page' => $node->content->last
            ]));
    }
}