<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core\Entity;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

abstract class Crud extends Action
{
    protected $_entityClass;
    protected $_actions = [
        'delete' => 'deleted',
    ];
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
        if (is_a($req->info->class, 'Chalk\Core\Behaviour\Trackable', true)) {
            $this->_sorts = [
                'createDate' => 'Created',
                'modifyDate' => 'Updated',
            ] + $this->_sorts;
        }
        if (is_a($req->info->class, 'Chalk\Core\Behaviour\Publishable', true)) {
            $this->_actions = [
                'publish' => 'published',
                'archive' => 'archived',
                'restore' => 'restored',
            ] + $this->_actions;
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

    public function index(Request $req, Response $res)
    {
        $parents = array_merge([get_class($this)], array_values(class_parents($this)));
        foreach ($parents as $parent) {
            $check = str_replace('\\Controller\\', '\\Model\\', $parent) . '\\Index';
            if (class_exists($check)) {
                $class = $check;
                break;
            }
        }
        if (!isset($class)) {
            return;
        }

        $model = new $class(
            $this->_entityClass,
            $this->_sorts,
            $this->_limits,
            $this->_batches
        );
        $req->view->model = $wrap = $this->em->wrap($model);
        
        if (!$req->isPost()) {
            $wrap->graphFromArray($req->queryParams());
            return;
        }

        $wrap->graphFromArray($req->bodyParams());

        if (!isset($this->_actions[$model->batch])) {
            throw new \Exception("Action '{$model->batch}' is invalid");
        }

        try {
            $method = "_{$model->batch}";
            $entities = $this->em($req->info)->all(['ids' => $model->selected]);
            foreach ($entities as $entity) {
                $this->$method($entity);
            }
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $model->batch = null;
            $this->notify("Some {$req->info->plural} cannot be {$this->_actions[$model->batch]} because they are in use", 'negative');
            return;
        }

        $this->notify("{$req->info->plural} were {$this->_actions[$model->batch]} successfully", 'positive');
        return $res->redirect($this->url->query(array(
            'selected' => null,
            'batch'    => null,
        ) + $req->bodyParams()));
    }

    public function create(Request $req, Response $res)
    {  
        $this->forward('update');
    }

    public function update(Request $req, Response $res)
    {
        $parents = array_merge([get_class($this)], array_values(class_parents($this)));
        foreach ($parents as $parent) {
            $check = str_replace('\\Controller\\', '\\Model\\', $parent) . '\\Update';
            if (class_exists($check)) {
                $class = $check;
                break;
            }
        }

        $model = new $class(
            $this->_entityClass
        );
        $req->view->model = $wrap = $this->em->wrap($model);
        $wrap->graphFromArray($req->bodyParams());

        $entity = isset($req->id)
            ? $this->em($req->info)->id($req->id)
            : $this->em($req->info)->create();
        if ($entity->isNew()) {
            $this->_create($entity);
        } else {
            $this->_update($entity);            
        }
        $req->view->entity = $wrap = $this->em->wrap($entity);

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->graphIsValid()) {
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
        ]));
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
            $this->$method($entity);
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->notify("{$req->info->singular} <strong>{$entity->previewName}</strong> could not be {$this->_actions[$req->type]} because it is in use", 'negative');
            return $res->redirect($this->url(array(
                'action' => 'update',
            )));
        }

        $this->notify("{$req->info->singular} <strong>{$entity->previewName}</strong> was {$this->_actions[$req->type]} successfully", 'positive');
        return $res->redirect($this->url(array(
            'action'    => null,
            'id'        => null,
        )));
    }

    public function delete(Request $req, Response $res)
    {
        $req->param('type', 'delete');
        return $this->forward('process');
    }

    protected function _create(Entity $entity)
    {}

    protected function _update(Entity $entity)
    {}

    protected function _delete(Entity $entity)
    {
        $this->em->remove($entity);
    }
}