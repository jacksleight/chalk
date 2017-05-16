<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

abstract class Crud extends Action
{
    protected $_entityClass;
    protected $_processes = [
        'delete' => 'deleted',
    ];

    public function preDispatch(Request $req, Response $res)
    {
        $req->view->info
            = $req->info
            = Chalk::info($this->_entityClass);
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

        $index = new $class($this->_entityClass);
        $req->view->index = $wrap = $this->em->wrap($index);
        $wrap->graphFromArray($req->queryParams());

        if (!isset($index->batch) || !count($index->entities)) {
            return;
        }
        if (!isset($this->_processes[$index->batch])) {
            throw new \Exception("Process '{$index->batch}' is invalid");
        }

        try {
            $method = "_process_{$index->batch}";
            foreach ($index->entities as $entity) {
                $this->$method($entity);
            }
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->notify("{$req->info->singular} <strong>{$entity->name}</strong> cannot be {$this->_processes[$index->batch]} because it is in use", 'negative');
            return;
        }

        $this->notify("{$req->info->plural} were {$this->_processes[$index->batch]} successfully", 'positive');
        return $res->redirect($this->url->query(array(
            'batch'     => null,
            'entityIds' => null,
        )));
    }

    public function create(Request $req, Response $res)
    {  
        $this->forward('update');
    }

    public function update(Request $req, Response $res)
    {
        $entity = isset($req->id)
            ? $this->em($req->info)->id($req->id)
            : $this->em($req->info)->create($req->queryParams());
        if ($entity->isNew()) {
            $this->_action_create($entity);
        } else {
            $this->_action_update($entity);            
        }
        $req->view->entity = $wrap = $this->em->wrap($entity);

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->graphIsValid()) {
            $this->notify("{$req->info->singular} <strong>{$entity->name}</strong> could not be saved, please check the messages below", 'negative');
            return;
        }

        try {
            $this->em->persist($entity);
            $this->em->flush();
        } catch (UniqueConstraintViolationException $e) {
            $this->notify("{$req->info->singular} could not be saved because <strong>{$entity->name}</strong> already exists", 'negative');
            return;
        }

        $this->notify("{$req->info->singular} <strong>{$entity->name}</strong> was saved successfully", 'positive');
        return $res->redirect($this->url(array(
            'action'    => null,
            'id'        => null,
        )));
    }

    public function process(Request $req, Response $res)
    {
        if (!isset($this->_processes[$req->type])) {
            throw new \Exception("Process '{$index->batch}' is invalid");
        }

        $entity = $this->em($req->info)->find($req->id);
        if (!isset($entity)) {
            throw new \Exception("Entity '{$req->info->name}' with ID '{$req->id}' does not exist");
        }

        try {
            $method = "_process_{$req->type}";
            $this->$method($entity);
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->notify("{$req->info->singular} <strong>{$entity->name}</strong> could not be {$this->_processes[$req->type]} because it is in use", 'negative');
            return $res->redirect($this->url(array(
                'action' => 'update',
            )));
        }

        $this->notify("{$req->info->singular} <strong>{$entity->name}</strong> was {$this->_processes[$req->type]} successfully", 'positive');
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

    protected function _action_create(\Toast\Entity $entity)
    {}

    protected function _action_update(\Toast\Entity $entity)
    {}

    protected function _process_delete(\Toast\Entity $entity)
    {
        $this->em->remove($entity);
    }
}