<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core\Entity as CoreEntity;
use Chalk\Core\Backend\Model;
use Chalk\Core\Backend\Controller\Entity;
use Coast\Request;
use Coast\Response;

class Domain extends Entity
{
	protected $_entityClass = 'Chalk\Core\Domain';
    protected $_actions = [
        'update' => 'updated',    
    ];
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

    protected function _delete(Request $req, Response $res, CoreEntity $entity)
    {
        throw new \Exception('Delete not permitted');
    }
}