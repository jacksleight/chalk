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
		$domains = $this->_ayre->em('Ayre\Entity\Domain')->fetchAll();
		$domain	 = $domains[0];
		$path 	 = $domain->root->slug . ($req->path ? '/' . $req->path : null);
		$node    = $this->_ayre->em('Ayre\Entity\Structure\Node')->fetchBySlugPath($domain, $path, true);
        
		return $res
			->html($this->view->render('index', ['page' => $node->content->last]));
    }
}