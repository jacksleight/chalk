<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

abstract class Crud extends Action
{
	protected $_entityClass;

	public function preDispatch(Request $req, Response $res)
	{
		$req->view->info
			= $req->info
			= Chalk::info($this->_entityClass);
	}

    public function index(Request $req, Response $res)
    {
        $class = "\\{$req->info->module->class}\\Model\\{$req->info->local->class}\\Index";
        if (!class_exists($class)) {
            $class = "\Chalk\Core\Model\Index";
        }
        $index = new $class();
        $req->view->index = $wrap = $this->em->wrap($index);
        $wrap->graphFromArray($req->queryParams());

        // if (!isset($index->batch)) {
        //     return;
        // }

        // try {
        //     $notice = null;
        //     foreach ($index->contents as $content) {
        //         if ($index->batch == 'publish') {
        //             $notice = 'published';
        //             $content->status = Chalk::STATUS_PUBLISHED;
        //         } else if ($index->batch == 'archive') {
        //             $notice = 'archived';
        //             $content->status = Chalk::STATUS_ARCHIVED;
        //         } else if ($index->batch == 'restore') {
        //             $notice = 'restored';
        //             $content->restore();
        //         } else if ($index->batch == 'delete') {
        //             $notice = 'deleted';
        //             $this->em->remove($content);
        //         }
        //     }
        //     $this->em->flush();
        // } catch (ForeignKeyConstraintViolationException $e) {
        //     if (isset($notice)) {
        //         $this->notify("{$req->info->singular} <strong>{$content->name}</strong> cannot be deleted because it is in use", 'negative');
        //     }
        //     return;
        // }

        // if (isset($notice)) {
        //     $this->notify("{$req->info->plural} were {$notice} successfully", 'positive');
        // }
        // return $res->redirect($this->url->query(array(
        //     'batch' => null,
        // )));
    }

	public function edit(Request $req, Response $res)
	{		
		$entity = isset($req->id)
			? $this->em($req->info)->id($req->id)
			: $this->em($req->info)->create();
		$req->view->entity = $wrap = $this->em->wrap($entity);

		if (!$req->isPost()) {
			return;
		}

		$wrap->graphFromArray($req->bodyParams());
		if (!$wrap->graphIsValid()) {
			$this->notify("{$req->info->singular} <strong>{$entity->name}</strong> could not be saved, please check the messages below", 'negative');
			return;
		}

		if (!$this->em->isPersisted($entity)) {
			$this->em->persist($entity);
		}
		try {
			$this->em->flush();
		} catch (UniqueConstraintViolationException $e) {
			$this->notify("{$req->info->singular} could not be saved because <strong>{$entity->name}</strong> already exists", 'negative');
			return;
		}

		$this->notify("{$req->info->singular} <strong>{$entity->name}</strong> was saved successfully", 'positive');
		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}

	public function delete(Request $req, Response $res)
	{
		$entity = $this->em($req->info)->find($req->id);

		$this->em->remove($entity);
		try {
			$this->em->flush();
		} catch (ForeignKeyConstraintViolationException $e) {
			$this->notify("{$req->info->singular} <strong>{$entity->name}</strong> could not be deleted because it is in use", 'negative');
			return $res->redirect($this->url(array(
				'action' => 'edit',
			)));
		}

		$this->notify("{$req->info->singular} <strong>{$entity->name}</strong> was deleted successfully", 'positive');
		return $res->redirect($this->url(array(
			'action'	=> null,
			'id'		=> null,
		)));
	}
}