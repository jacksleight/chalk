<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core\Backend\Model;
use Chalk\Core\Entity as CoreEntity;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

abstract class Entity extends Action
{
    protected $_entityClass;
    protected $_actions;
    protected $_sorts = [];
    protected $_limits = [
        50  => '50',
        100 => '100',
        200 => '200',
    ];
    protected $_batches = [
        'delete' => 'Delete',
    ];

    public function preDispatch(Request $req, Response $res)
    {
        $req->view->info
            = $req->info
            = Chalk::info($this->_entityClass);
        $req->model->selectedType = $req->info->name;

        $this->_actions = \Coast\array_filter_null($this->_actions($req));
        $req->view->actions
            = $req->actions
            = array_keys($this->_actions);

        if (is_a($req->info->class, 'Chalk\Core\Behaviour\Trackable', true)) {
            $this->_sorts = [
                'createDate' => 'Created',
                'modifyDate' => 'Updated',
            ] + $this->_sorts;
        }
        if (is_a($req->info->class, 'Chalk\Core\Behaviour\Publishable', true)) {
            $this->_sorts = [
                'publishDate' => 'Published',
                'status'      => 'Status',
            ] + $this->_sorts;
            $this->_batches = [
                'publish'   => 'Publish',
                'archive'   => 'Archive',
                'restore'   => 'Restore',
            ] + $this->_batches;
        }
    }

    protected function _actions(Request $req)
    {
        $actions = [
            'create' => 'created',
            'update' => 'updated',
            'delete' => 'deleted',
            'batch'  => 'batched',
        ];
        if (is_a($req->info->class, 'Chalk\Core\Behaviour\Publishable', true)) {
            $actions = $actions + [
                'publish' => 'published',
                'archive' => 'archived',
                'restore' => 'restored',
            ];
        }
        if (in_array($req->model->mode, ['select-one', 'select-all']) ) {
            $actions = [
                $req->model->mode => 'selected',
            ];
        }
        return $actions;
    }

    public function index(Request $req, Response $res)
    {
        $req->model->data(
            $this->_sorts,
            $this->_limits,
            $this->_batches
        );
    }

    public function batch(Request $req, Response $res)
    {   
        if (!$req->isPost()) {
            throw new \Chalk\Exception("Batch action only accepts POST requests");
        }

        $req->model->graphFromArray($req->bodyParams());

        if (!isset($this->_actions[$req->model->batch])) {
            throw new \Exception("Action '{$req->model->batch}' is invalid");
        }

        try {
            $method = "_{$req->model->batch}";
            $entities = $this->em($req->info)->all(['ids' => $req->model->selected]);
            foreach ($entities as $entity) {
                $this->$method($req, $res, $entity, $req->model);
            }
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $req->model->batch = null;
            $this->notify("Some {$req->info->plural} cannot be {$this->_actions[$req->model->batch]} because they are in use", 'negative');
            return;
        }

        $this->notify("{$req->info->plural} were {$this->_actions[$req->model->batch]} successfully", 'positive');
        return $res->redirect($this->url([
            'action'   => null,
        ]) . $this->url->query(array(
            'selected' => null,
            'batch'    => null,
        ) + $req->bodyParams()));
    }

    public function select(Request $req, Response $res)
    {
        $entities = $this->em($req->model->selectedType)->all(['ids' => $req->model->selected()]);
        $data = [];
        foreach ($entities as $entity) {
            $data[] = [
                'id'    => $entity->id,
                'name'  => $entity->previewName,
                'card'  => $this->view->render('element/card', [
                    'entity' => $entity,
                ], 'core')->toString(),
            ];
        }
        $req->data->entites = $data;
    }

    public function create(Request $req, Response $res)
    {  
        $this->forward('update');
    }

    public function update(Request $req, Response $res)
    {
        $entity = isset($req->id)
            ? $this->em($req->info)->id($req->id)
            : $this->em($req->info)->create();
        if ($entity->isNew()) {
            $this->_create($req, $res, $entity, $req->model);
        } else {
            $this->_update($req, $res, $entity, $req->model);
        }
        $req->view->entity = $entityWrap = $this->em->wrap($entity);

        if (!$req->isPost()) {
            return;
        }

        $entityWrap->graphFromArray($req->bodyParams());
        if (!$entityWrap->graphIsValid()) {
            $this->notify("{$req->info->singular} <strong>{$entity->previewName}</strong> could not be saved, please check the messages below", 'negative');
            return;
        }

        try {
            $this->em->persist($entity);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $this->notify("{$req->info->singular} could not be saved because <strong>{$entity->previewName}</strong> already exists", 'negative');
            return;
        }

        $this->notify("{$req->info->singular} <strong>{$entity->previewName}</strong> was saved successfully", 'positive');
        return $res->redirect($this->url([
            'id' => $entity->id,
        ]) . $this->url->query([
            'tagsList' => $req->model->tagsList,
        ], true));
    }

    public function process(Request $req, Response $res)
    {
        if (!isset($this->_actions[$req->type])) {
            throw new \Exception("Action '{$req->type}' is invalid");
        }

        $entity = $this->em($req->info)->find($req->id);
        if (!isset($entity)) {
            throw new \Exception("Entity '{$req->info->name}' with ID '{$req->id}' does not exist");
        }

        try {
            $method = "_{$req->type}";
            $this->$method($req, $res, $entity, $req->model);
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->notify("{$req->info->singular} <strong>{$entity->previewName}</strong> could not be {$this->_actions[$req->type]} because it is in use", 'negative');
            return $res->redirect($this->url(array(
                'action' => 'update',
            )));
        }

        $this->notify("{$req->info->singular} <strong>{$entity->previewName}</strong> was {$this->_actions[$req->type]} successfully", 'positive');
        if (isset($req->redirect)) {
            return $res->redirect($req->redirect);
        } else if ($req->type == 'delete') {
            return $res->redirect($this->url([
                'action' => null,
                'id'     => null,
            ]) . $this->url->query([
                'tagsList' => $req->model->tagsList,
            ], true));
        } else {
            return $res->redirect($this->url([
                'action' => 'update',
            ]) . $this->url->query([
                'tagsList' => $req->model->tagsList,
            ], true));
        }
    }

    public function delete(Request $req, Response $res)
    {
        $req->param('type', 'delete');
        return $this->forward('process');
    }

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

    protected function _create(Request $req, Response $res, CoreEntity $entity)
    {
        if ($req->info->is->tagable) {
            if (count($req->model->tags)) {
                $tags = $this->em('core_tag')->all(['ids' => $req->model->tags]);
                foreach ($tags as $tag) {
                    $entity->tags[] = $tag;
                }
            }
        }
    }

    protected function _update(Request $req, Response $res, CoreEntity $entity)
    {}

    protected function _delete(Request $req, Response $res, CoreEntity $entity)
    {
        $this->em->remove($entity);
    }

    protected function _publish(Request $req, Response $res, CoreEntity $entity)
    {
        $entity->status = Chalk::STATUS_PUBLISHED;
    }

    protected function _archive(Request $req, Response $res, CoreEntity $entity)
    {
        $entity->status = Chalk::STATUS_ARCHIVED;
    }

    protected function _restore(Request $req, Response $res, CoreEntity $entity)
    {
        $entity->restore();
    }
}
