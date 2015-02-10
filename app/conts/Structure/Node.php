<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Controller\Structure;

use Chalk\Chalk,
    Chalk\Core,
    Coast\App\Controller\Action,
    Coast\Request,
    Coast\Response;

class Node extends Action
{
    public function add(Request $req, Response $res)
    {
        $req->view->info = Chalk::info('Chalk\Core\Content');

        $wrap = $this->em->wrap($index = new \Chalk\Core\Model\Index());
        $wrap->graphFromArray($req->queryParams());
        $req->view->index = $wrap;

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (isset($req->node)) {
            $parent = $this->em('Chalk\Core\Structure\Node')->id($req->node);
        } else {
            $parent = $this->em('Chalk\Core\Structure')->id($req->structure)->root;
        }

        foreach ($index->contents as $content) {
            $node = new \Chalk\Core\Structure\Node();
            $node->parent  = $parent;
            $node->content = $content->master;
            $this->em->persist($node);
            $this->em->flush();
        }

        $this->notify("Content was added successfully", 'positive');
        if (isset($req->node)) {
            return $res->redirect($this->url(array(
                'action' => 'edit',
            )));
        } else {
            return $res->redirect($this->url(array(
                'action' => 'index',
                'node'   => null,
            ), 'structure'));
        }
    }

    public function edit(Request $req, Response $res)
    {
        if (isset($req->node)) {
            $node = $this->em('Chalk\Core\Structure\Node')->id($req->node);
        } else {
            $node          = $this->em('Chalk\Core\Structure\Node')->create();
            $node->parent  = $this->em('Chalk\Core\Structure\Node')->id($req->parent);
            $node->content = $this->em($req->type)->create();
        }
        $req->view->node = $wrap = $this->em->wrap($node);

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->isValid()) {
            return;
        }

        if (!$this->em->isPersisted($node)) {
            $this->em->persist($node);
        }
        $this->em->flush();

        $this->notify(\Chalk\Chalk::info($node->content)->singular . " <strong>{$node->content->name}</strong> was saved successfully", 'positive');
        return $res->redirect($this->url([
            'node' => $node->id,
        ]));
    }

    public function delete(Request $req, Response $res)
    {
        $node = $this->em('Chalk\Core\Structure\Node')->id($req->node);

        $parent = $node->parent;
        $parent->id;
        foreach ($node->children as $child) {
            $node->children->removeElement($child);
            $child->parent  = $parent;
            $child->sort    = \Chalk\Core\Structure\Node::SORT_MAX;
        }
        $this->em->remove($node);
        $this->em->flush();

        $this->notify(\Chalk\Chalk::info($node->content)->singular . " <strong>{$node->content->name}</strong> was removed successfully", 'positive');
        return $res->redirect($this->url(array(
            'action'    => 'index',
            'structure' => $node->structure->id,
        ), 'structure', true));
    }
}