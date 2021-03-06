<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core\Backend\Model;
use Chalk\Entity as ChalkEntity;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

abstract class Entity extends Action
{
    protected $_entityClass;

    public function preDispatch(Request $req, Response $res)
    {
        $this->info = Chalk::info($this->_entityClass);

        $actions       = \Coast\array_filter_null($this->_actions($req));
        $this->actions = array_keys($actions);
        $this->labels  = $actions;

        $req->view->info    = $this->info;
        $req->view->actions = $this->actions;
        $req->view->labels  = $this->labels;
    }

    protected function _actions()
    {
        $actions = [
            'create'    => 'created',
            'update'    => 'updated',
            'delete'    => 'deleted',
            'batch'     => 'batched',
            'duplicate' => 'duplicated',
        ];
        if ($this->info->is->publishable) {
            $actions = $actions + [
                'publish' => 'published',
                'archive' => 'archived',
                'restore' => 'restored',
            ];
        }
        if (in_array($this->model->mode, ['select-one', 'select-all']) ) {
            $actions = [
                $this->model->mode => 'selected',
            ];
        }
        return $actions;
    }

    protected function _sorts()
    {
        $sorts = [];
        if ($this->info->is->trackable) {
            $sorts = [
                'createDate' => 'Created',
                'updateDate' => 'Updated',
            ] + $sorts;
        }
        if ($this->info->is->publishable) {
            $sorts = [
                'publishDate' => 'Published',
                'status'      => 'Status',
            ] + $sorts;
        }
        return $sorts;
    }

    protected function _limits()
    {
        $limits = [
            50  => '50',
            100 => '100',
            200 => '200',
        ];
        return $limits;
    }

    protected function _batches()
    {
        $batches = [
            'delete' => 'Delete',
        ];
        if ($this->info->is->publishable) {
            $batches = [
                'publish'   => 'Publish',
                'archive'   => 'Archive',
                'restore'   => 'Restore',
            ] + $batches;
        }
        return $batches;
    }

    protected function _redirect(Request $req, Response $res, ChalkEntity $entity)
    {
        return $this->url([
            'action' => 'update',
            'id'     => $entity->id,
        ]);
    }

    protected function _redirectParams(Request $req, Response $res, ChalkEntity $entity)
    {
        $redirectParams = [];
        if ($this->info->is->tagable) {
            $redirectParams = [
                'tagsList' => $this->model->tagsList,
            ] + $redirectParams;
        }
        return $redirectParams;
    }

    public function index(Request $req, Response $res)
    {
        $this->model->options(
            $this->_sorts(),
            $this->_limits(),
            $this->_batches()
        );
    }

    public function batch(Request $req, Response $res)
    {
        if (!$req->isPost()) {
            throw new \Chalk\Exception("Batch action only accepts POST requests");
        }

        $this->model->graphFromArray($req->bodyParams());

        if (!array_intersect([$this->model->batch], $this->actions)) {
            throw new \Exception("Action '{$this->model->batch}' is invalid");
        }

        try {
            $method = "_{$this->model->batch}";
            $entities = $this->em($this->info)->all(['ids' => $this->model->selected]);
            foreach ($entities as $entity) {
                $this->$method($req, $res, $entity);
            }
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->model->batch = null;
            $this->notify("Some {$this->info->plural} cannot be {$this->labels[$this->model->batch]} because they are in use", 'negative');
            return;
        }

        $this->notify(count($entities) . " " . strtolower($this->info->plural) . " {$this->labels[$this->model->batch]} successfully", 'positive');
        return $res->redirect($this->url([
            'action'   => null,
        ]) . $this->url->query(array(
            'selectedList' => null,
            'batch'        => null,
        ) + $req->bodyParams()));
    }

    public function select(Request $req, Response $res)
    {
        $entities = $this->em($this->model->selectedType)->all(['ids' => $this->model->selected()]);
        $items = [];
        foreach ($entities as $entity) {
            $ref = Chalk::ref([
                'type' => Chalk::info($entity)->name,
                'id'   => $entity->id,
                'sub'  => Chalk::sub($this->model->selectedSub),
            ]);
            $items[] = [
                'ref'       => $ref,
                'refString' => Chalk::ref($ref, true),
                'card'      => $this->view->render('element/card', [
                    'ref' => $ref,
                ], 'core')->toString(),
            ];
        }
        $req->data->items = $items;
    }

    public function create(Request $req, Response $res)
    {
        $this->forward('update');
    }

    public function update(Request $req, Response $res)
    {
        $entity = isset($req->id)
            ? $this->em($this->info)->id($req->id, $this->model->toArray())
            : $this->em($this->info)->create();
        if ($entity->isNew()) {
            $this->_create($req, $res, $entity);
        } else {
            $this->_update($req, $res, $entity);
        }
        $entity = $this->em->wrap($entity);

        $req->view->entity = $entity;

        if (!$req->isPost()) {
            return;
        }
        
        $entity->graphFromArray($req->bodyParams());
        if (!$entity->graphIsValid()) {
            $this->notify("{$this->info->singular} <strong>{$entity->previewName}</strong> could not be saved, please check the messages below", 'negative');
            return;
        }

        try {
            $this->em->persist($entity->getObject());
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $this->notify("{$this->info->singular} could not be saved because <strong>{$entity->previewName}</strong> already exists", 'negative');
            return;
        }

        $this->notify("{$this->info->singular} <strong>{$entity->previewName}</strong> saved successfully", 'positive');
        return $res->redirect(
            $this->_redirect($req, $res, $entity->getObject()) .
            $this->url->query($this->_redirectParams($req, $res, $entity->getObject()), true)
        );
    }

    public function process(Request $req, Response $res)
    {
        if (!array_intersect([$req->action], $this->actions)) {
            throw new \Exception("Action '{$req->action}' is invalid");
        }

        $entity = $this->em($this->info)->id($req->id);
        if (!isset($entity)) {
            throw new \Exception("Entity '{$this->info->name}' with ID '{$req->id}' does not exist");
        }

        try {
            $method = "_{$req->action}";
            $new = $this->$method($req, $res, $entity);
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->notify("{$this->info->singular} <strong>{$entity->previewName}</strong> could not be {$this->labels[$req->action]} because it is in use", 'negative');
            return $res->redirect($this->url(array(
                'action' => 'update',
            )));
        }

        $this->notify("{$this->info->singular} <strong>{$entity->previewName}</strong> was {$this->labels[$req->action]} successfully", 'positive');
        if ($req->action == 'delete') {
            return $res->redirect($this->url([
                'action' => null,
                'id'     => null,
            ]) . $this->url->query($this->_redirectParams($req, $res, $entity), true));
        } else if (isset($new)) {
            return $res->redirect($this->url([
                'action' => 'update',
                'id'     => $new->id,
            ]) . $this->url->query($this->_redirectParams($req, $res, $new), true));
        } else {
            return $res->redirect($this->url([
                'action' => 'update',
            ]) . $this->url->query($this->_redirectParams($req, $res, $entity), true));
        }
    }

    public function delete(Request $req, Response $res)
    {
        $req->param('action', 'delete');
        return $this->forward('process');
    }

    public function duplicate(Request $req, Response $res)
    {
        $req->param('action', 'duplicate');
        return $this->forward('process');
    }

    public function publish(Request $req, Response $res)
    {
        $req->param('action', 'publish');
        return $this->forward('process');
    }

    public function archive(Request $req, Response $res)
    {
        $req->param('action', 'archive');
        return $this->forward('process');
    }

    public function restore(Request $req, Response $res)
    {
        $req->param('action', 'restore');
        return $this->forward('process');
    }

    protected function _create(Request $req, Response $res, ChalkEntity $entity)
    {
        if ($this->info->is->tagable) {
            if (count($this->model->tags)) {
                $tags = $this->em('core_tag')->all(['ids' => $this->model->tags]);
                foreach ($tags as $tag) {
                    $entity->tags[] = $tag;
                }
            }
        }
    }

    protected function _update(Request $req, Response $res, ChalkEntity $entity)
    {}

    protected function _delete(Request $req, Response $res, ChalkEntity $entity)
    {
        $this->em->remove($entity);
    }

    protected function _duplicate(Request $req, Response $res, ChalkEntity $entity)
    {
        $entity = $entity->duplicate();
        $this->em->persist($entity);
        return $entity;
    }

    protected function _publish(Request $req, Response $res, ChalkEntity $entity)
    {
        $entity->status = Chalk::STATUS_PUBLISHED;
    }

    protected function _archive(Request $req, Response $res, ChalkEntity $entity)
    {
        $entity->status = Chalk::STATUS_ARCHIVED;
    }

    protected function _restore(Request $req, Response $res, ChalkEntity $entity)
    {
        $entity->restore();
    }
}
