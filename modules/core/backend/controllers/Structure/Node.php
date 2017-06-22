<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller\Structure;

use Chalk\Chalk;
use Chalk\Core\Backend\Controller\Entity;
use Coast\Request;
use Coast\Response;

class Node extends Entity
{
    protected $_entityClass = 'Chalk\Core\Structure\Node';

    public function update(Request $req, Response $res)
    {
        if (isset($req->id)) {
            $entity = isset($req->id)
                ? $this->em($this->info)->id($req->id)
                : $this->em($this->info)->create();
            $content = $entity->content;
            $req->pathParam('id', $content->id);
            $req->queryParam('node', $entity->id);
            $route = $this->url($content, null, true);
        } else {
            if (isset($this->model->node)) {
                $req->queryParam('node', $this->model->node);
            } else {
                $structure = $this->em('core_structure')->one();
                $req->queryParam('node', $structure->root->id);
            }
            if (isset($this->model->nodeType)) {
                $route = $this->url($this->model->nodeType, 0, true);
            } else {
                throw new \Exception('No content type specificed for new node');
            }
        }
        $req->queryParam('nodeUi', true);

        $params = $route['params'];
        return $this->forward('update', $params['controller'], $params['group']);
    }
}