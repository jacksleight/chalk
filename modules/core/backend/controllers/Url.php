<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core\Backend\Model;
use Chalk\Entity;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;
use Chalk\Core\Backend\Model\Url\Quick;
use Coast\Url as CoastUrl;

class Url extends Content
{
	protected $_entityClass = 'Chalk\Core\Url';

    public function quick(Request $req, Response $res)
    {
        if (!$req->isPost()) {
            throw new \Chalk\Exception("Quick action only accepts POST requests");
        }

        $model = new Quick();
        $req->view->model = $modelWrap = $this->em->wrap($model);

        $modelWrap->graphFromArray($req->bodyParams());
        if (!$modelWrap->graphIsValid()) {
            $this->notify("{$this->info->singular} could not be added, please try again", 'negative');
            return $res->redirect($this->url(array(
                'action' => 'index',
            )));
            return;
        }

        $content = $this->em($this->info)->one([
            'url' => $model->url,
        ]);
        if ($content) {
            $this->model->redirect->queryParam('selectedList', $content->id);
            return $res->redirect($this->model->redirect);
        }

        $content = $this->em($this->info)->create();
        $content->status = \Chalk\Chalk::STATUS_PUBLISHED;
        $content->fromArray([
            'name' => $model->name,
            'url'  => $model->url,
        ]);

        $this->em->persist($content);
        $this->em->flush();

        $this->model->redirect->queryParam('selectedList', $content->id);
        return $res->redirect($this->model->redirect);
    }

    protected function _create(Request $req, Response $res, Entity $entity)
    {
        parent::_create($req, $res, $entity);
        if (isset($req->url)) {
            $entity->url = new CoastUrl($req->url);
        }
    }
}