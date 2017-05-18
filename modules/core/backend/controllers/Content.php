<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core;
use Chalk\Core\Entity;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

abstract class Content extends Crud
{
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

    protected function _publish(Entity $entity)
    {
        $entity->status = Chalk::STATUS_PUBLISHED;
    }

    protected function _archive(Entity $entity)
    {
        $entity->status = Chalk::STATUS_ARCHIVED;
    }

    protected function _restore(Entity $entity)
    {
        $entity->restore();
    }
}