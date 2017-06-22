<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core;
use Chalk\Core\Structure\Node;
use Chalk\Entity as ChalkEntity;
use Chalk\Core\Backend\Model;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

abstract class Content extends Entity
{
    protected function _redirectParams(Request $req, Response $res, ChalkEntity $entity)
    {
        $redirectParams = parent::_redirectParams($req, $res, $entity);
        if (isset($this->model->node) && !$this->model->nodeUi) {
            $redirectParams = [
                'node' => $entity->nodes[0]->id,
            ] + $redirectParams;
        }
        return $redirectParams;
    }

    protected function _create(Request $req, Response $res, ChalkEntity $entity)
    {
        parent::_create($req, $res, $entity);
        if (isset($this->model->node)) {
            $parent = $this->em('core_structure_node')->id($this->model->node);
            $node = new Node();
            $node->structure = $parent->structure;
            $node->parent    = $parent;
            $node->content   = $entity;
            $entity->nodes[] = $node;
        }
    }
}