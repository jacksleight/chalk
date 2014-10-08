<?php
/*
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk;

use Chalk,
	Chalk\Module\Standard;

class Core extends Standard
{
	public function chalk(Chalk $chalk)
	{
		$entity = Chalk::entity($this);

        $chalk->em->dir($entity->name, $this->dir('app/lib'));
		$chalk->view->dir($entity->name, $this->dir('app/views/admin'));
        $chalk->controller->nspace($entity->name, "{$entity->class}\\Controller");
		$chalk
			->contentClass("{$entity->class}\\Page")
			->contentClass("{$entity->class}\\File");
	}
}