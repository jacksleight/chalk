<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core;
use Chalk\Core\Entity;
use Chalk\Core\Backend\Model;
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

    protected function _create(Request $req, Response $res, Entity $entity, Model $model = null)
    {
        parent::_create($req, $res, $entity, $model);
        if (count($model->tags)) {
            $tags = $this->em('core_tag')->all(['ids' => $model->tags]);
            foreach ($tags as $tag) {
                $entity->tags[] = $tag;
            }
        }
    }

    protected function _publish(Request $req, Response $res, Entity $entity, Model $model = null)
    {
        $entity->status = Chalk::STATUS_PUBLISHED;
    }

    protected function _archive(Request $req, Response $res, Entity $entity, Model $model = null)
    {
        $entity->status = Chalk::STATUS_ARCHIVED;
    }

    protected function _restore(Request $req, Response $res, Entity $entity, Model $model = null)
    {
        $entity->restore();
    }
}