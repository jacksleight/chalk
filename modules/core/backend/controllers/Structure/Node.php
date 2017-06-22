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
            $req->queryParam('nodeUi', true);
            $route = $this->url($content, null, true);
        } else {
            $req->queryParam('node', 1);
            $req->queryParam('nodeUi', true);
            $route = $this->url('root_page', 0, true);
        }


        

        
        $params  = $route['params'];
        return $this->forward('update', $params['controller'], $params['group']);
    }

    // public function add(Request $req, Response $res)
    // {
    //     $filters = $this->chalk->module('core')->contentList('core_node');
    //     $info = isset($req->type)
    //         ? Chalk::info($req->type)
    //         : $filters->first();
    //     $req->queryParam('type', $info->name);

    //     $class = "\\{$info->module->class}\\Model\\{$info->local->class}\\Index";
    //     if (!class_exists($class)) {
    //         $class = "\Chalk\Core\Backend\Model\Content\Index";
    //     }
    //     $index = new $class();

    //     $wrap = $this->em->wrap($index);
    //     $wrap->graphFromArray($req->queryParams());
    //     $req->view->index   = $wrap;
    //     $req->view->filters = $filters;

    //     if (!$req->isPost() && !$index->contentNew) {
    //         return;
    //     }

    //     $wrap->graphFromArray($req->bodyParams());
    //     if (isset($req->node)) {
    //         $parent = $this->em('Chalk\Core\Structure\Node')->id($req->node);
    //     } else {
    //         $parent = $this->em('Chalk\Core\Structure')->id($req->structure)->root;
    //     }

    //     foreach ($index->contents as $content) {
    //         $node = new \Chalk\Core\Structure\Node();
    //         $node->parent  = $parent;
    //         $node->content = $content;
    //         $this->em->persist($node);
    //     }
    //     $this->em->flush();

    //     $this->notify("Content was added successfully", 'positive');
    //     if (isset($req->node)) {
    //         $req->data->redirect = $this->url([
    //             'action' => 'edit',
    //         ])->toString();
    //     } else {
    //         $req->data->redirect = $this->url([
    //             'action' => 'index',
    //             'node'   => null,
    //         ], 'core_structure')->toString();
    //     }
    // }

    // public function edit(Request $req, Response $res)
    // {
    //     if (isset($req->node)) {
    //         $node = $this->em('Chalk\Core\Structure\Node')->id($req->node);
    //     } else {
    //         $node          = $this->em('Chalk\Core\Structure\Node')->create();
    //         $node->parent  = $this->em('Chalk\Core\Structure\Node')->id($req->parent);
    //         $node->content = $this->em($req->type)->create();
    //     }
    //     $req->view->node = $wrap = $this->em->wrap($node);

    //     if (!$req->isPost()) {
    //         return;
    //     }

    //     $wrap->graphFromArray($req->bodyParams());
    //     if (!$wrap->graphIsValid()) {
    //         return;
    //     }

    //     if (!$this->em->isPersisted($node)) {
    //         $this->em->persist($node);
    //     }
    //     $this->em->flush();

    //     $this->notify(Chalk::info($node->content)->singular . " <strong>{$node->content->name}</strong> was saved successfully", 'positive');
    //     return $res->redirect($this->url([
    //         'node' => $node->id,
    //     ]));
    // }

    // public function delete(Request $req, Response $res)
    // {
    //     $node = $this->em('Chalk\Core\Structure\Node')->id($req->node);

    //     $parent = $node->parent;
    //     $parent->id;
    //     foreach ($node->children as $child) {
    //         $node->children->removeElement($child);
    //         $child->parent  = $parent;
    //         $child->sort    = \Chalk\Core\Structure\Node::VALUE_MAX;
    //     }
    //     $this->em->remove($node);
    //     $this->em->flush();

    //     $this->notify(Chalk::info($node->content)->singular . " <strong>{$node->content->name}</strong> was removed successfully", 'positive');
    //     return $res->redirect($this->url(array(
    //         'action'    => 'index',
    //         'structure' => $node->structure->id,
    //     ), 'core_structure', true));
    // }
}