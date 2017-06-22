<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Entity as ChalkEntity;
use Chalk\Core\Backend\Model;
use Chalk\Core\Backend\Controller\Entity;
use Coast\Request;
use Coast\Response;

class Profile extends Entity
{
	protected $_entityClass = 'Chalk\Core\User';
    protected $_actions = [];
    protected $_batches = [];

	public function update(Request $req, Response $res)
	{
		$req->id = $this->user->id;
		return parent::update($req, $res);
	}

    public function delete(Request $req, Response $res)
    {
        throw new \Exception('Delete not permitted');
    }

    protected function _delete(Request $req, Response $res, ChalkEntity $entity)
    {
        throw new \Exception('Delete not permitted');
    }
}