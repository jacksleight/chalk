<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller\Setting;

use Chalk\Chalk;
use Chalk\Core\Entity,
	Chalk\Core\Backend\Controller\Crud,
	Coast\Request,
	Coast\Response;

class Domain extends Crud
{
	protected $_entityClass = 'Chalk\Core\Domain';
    protected $_actions = [];
    protected $_batches = [];

	public function index(Request $req, Response $res)
	{
		return $res->redirect($this->url([
			'action' => 'update',
			'id'     => 1,
		]));
	}

    public function delete(Request $req, Response $res)
    {
        throw new \Exception('Delete not permitted');
    }

    protected function _delete(Entity $entity)
    {
        throw new \Exception('Delete not permitted');
    }
}