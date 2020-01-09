<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller\Structure;

use Chalk\Chalk;
use Chalk\Core\Backend\Controller\Entity;
use Chalk\Core\Page;
use Coast\Request;
use Coast\Response;
use Chalk\Core\Structure\Node\Iterator;
use Chalk\Core\Structure\Node as StructureNode;
use Chalk\Entity as ChalkEntity;

class Node extends Entity
{
    protected $_entityClass = 'Chalk\Core\Structure\Node';

    protected function _sorts()
    {
        return [];
    }

    public function update(Request $req, Response $res)
    {
        if (isset($req->id)) {
            $entity = isset($req->id)
                ? $this->em($this->info)->id($req->id)
                : $this->em($this->info)->create();
            $content = $entity->content;
            $this->em->detach($content);
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

    public function select(Request $req, Response $res)
    {
        $nodes    = $this->em($this->model->selectedType)->all(['ids' => $this->model->selected()]);
        $ids      = array_map(function($v) { return $v->content->id; }, $nodes);
        $entities = $this->em('core_content')->all(['ids' => $ids]);

        $data = [];
        foreach ($entities as $entity) {
            $data[] = [
                'type'  => Chalk::info($entity)->name,
                'id'    => $entity->id,
                'name'  => $entity->previewName,
                'card'  => $this->view->render('element/card', [
                    'entity' => $entity,
                ], 'core')->toString(),
            ];
        }
        $req->data->entities = $data;
    }

    public function organise(Request $req, Response $res)
    {
        if (!$req->isPost()) {
            return;
        }
        if (!$req->nodeData) {
            $req->nodeData = '[]';
        }

        $nodes = $this
            ->em('Chalk\Core\Structure\Node')
            ->all();
        $map = [];
        foreach ($nodes as $node) {
            $map[$node->id] = $node;
        }

        $nodeData = json_decode($req->nodeData);
        foreach ($nodeData as $root => $data) {
            $root = $this
                ->em('Chalk\Core\Structure\Node')
                ->id($root);
            $it = new \RecursiveIteratorIterator(
                new Iterator($data),
                \RecursiveIteratorIterator::SELF_FIRST);
            $stack = [];
            foreach ($it as $i => $value) {
                if (!isset($value->id)) {
                    continue;
                }
                array_splice($stack, $it->getDepth(), count($stack), array($value));
                $depth  = $it->getDepth();
                $parent = $depth > 0
                    ? $stack[$depth - 1]
                    : $root;
                $node = $map[$value->id];
                $node->parent->children->removeElement($node);
                $node->parent = $map[$parent->id];
                $node->sort = $i * 10;
            }
        }
        $this->em->flush();

        $this->notify("Content was saved successfully", 'positive');
        $req->data->reload = true;
    }

    public function existing(Request $req, Response $res)
    {
        $structure = $this->em('core_structure')->one();
        $parent    = $structure->root;

        $entities = $this->em($this->model->selectedType)->all(['ids' => $this->model->selected]);
        foreach ($entities as $entity) {
            $node = new StructureNode();
            $node->structure = $parent->structure;
            $node->parent    = $parent;
            $node->content   = $entity;
            $node->isHidden  = true;
            $entity->nodes->add($node);
            $this->em->persist($node);
        }
        $this->em->flush();

        $req->data->redirect = $this->url([
            'action' => null,
        ])->toString();
    }

    protected function _duplicate(Request $req, Response $res, ChalkEntity $entity)
    {
        $content = $entity->content->duplicate();

        $node = new StructureNode();
        $node->structure = $this->em('core_structure')->id($entity->structure->id);
        $node->content   = $content;
        if (isset($entity->parent)) {
            $node->parent = $this->em('core_structure_node')->id($entity->parent->id);
            $node->sort   = $entity->sort + 1;
        } else {
            $node->parent = $entity;
        }
        $content->nodes->add($node);

        $this->em->persist($content);
        $this->em->persist($node);

        return $node;
    }

    protected function _delete(Request $req, Response $res, ChalkEntity $entity)
    {
        $content = $entity->content;

        $this->em->remove($entity);
        if ($content instanceof Page && count($content->nodes) == 1) {
            $this->em->remove($content);
        }
    }
}
