<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

abstract class Content extends Crud
{
    protected $_entityClass;
    protected $_processes = [
        'delete'  => 'deleted',
        'publish' => 'published',
        'archive' => 'archived',
        'restore' => 'restored',
    ];

    public function publish(Request $req, Response $res)
    {
        $req->param('type', 'publish');
        return $this->forward('process');
    }

    public function archive(Request $req, Response $res)
    {
        $req->param('type', 'archive');
        return $this->forward('process');
    }

    public function restore(Request $req, Response $res)
    {
        $req->param('type', 'restore');
        return $this->forward('process');
    }

    protected function _process_publish(\Toast\Entity $entity)
    {
        $entity->status = Chalk::STATUS_PUBLISHED;
    }

    protected function _process_archive(\Toast\Entity $entity)
    {
        $entity->status = Chalk::STATUS_ARCHIVED;
    }

    protected function _process_restore(\Toast\Entity $entity)
    {
        $entity->restore();
    }
}