<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Repository;

use Chalk\Repository,
	Chalk\Core\Domain,
	Chalk\Core\Menu,
    Chalk\Behaviour\Publishable,
	Chalk\Core\Structure as CoreStructure;

class Structure extends Repository
{
	use Publishable\Repository {
        Publishable\Repository::queryModifier as publishableQueryModifier;
    }

    public function fetchNodes(CoreStructure $structure, $depth = null)
    {
    	$repo = $this->_em->getRepository('Chalk\Core\Structure\Node');
        return $repo->fetchAll([
			'structure'	=> $structure,
			'depth'		=> $depth,
        ]);
    }

    public function fetchTree(CoreStructure $structure, $depth = null)
    {
        $repo = $this->_em->getRepository('Chalk\Core\Structure\Node');
        $nodes = $repo->fetchAll([
			'structure'	=> $structure,
			'depth'		=> $depth,
        ]);
        return [$nodes[0]];
    }
}