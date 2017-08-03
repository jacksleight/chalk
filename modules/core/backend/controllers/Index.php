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
use Chalk\Nav;

class Index extends Action
{
    public function index(Request $req, Response $res)
    {
        $items = $this->nav->children('root');
        if (!count($items)) {
            throw new \Exception('No route for redirection');
        }
        $item = current($items);
        return $res->redirect($item['url']);
    }
    
    public function about(Request $req, Response $res)
    {}
    
    public function frontend(Request $req, Response $res)
    {
        Chalk::isDual(true);
        $entity = $this->em($req->entityType)->id($req->entityId);
        if (!$entity) {
            throw new \Exception();
        }
        $url = $this->frontend->url($entity);
        if ($url !== false) {
            return $res->redirect($url);
        }
        $req->view->entity = $entity;
        $res->status(404);
    }

    public function backend(Request $req, Response $res)
    {
        if ($req->entityId == 0) {
            $entity = [
                '__CLASS__' => Chalk::info($req->entityType)->class,
                'id'        => 0,
            ];
        } else {
            $entity = $this->em($req->entityType)->id($req->entityId);
            if (!$entity) {
                throw new \Exception();
            }
        }
        $url = $this->url($entity);
        if ($url !== false) {
            return $res->redirect($url);
        }
        $req->view->entity = $entity;
        $res->status(404);
    }

    public function prefs(Request $req, Response $res)
    {
        foreach ($req->queryParams() as $name => $value) {
            $this->user->pref($name, $value);
        }
        $this->em->flush();
        return true;
    }

    public function publish(Request $req, Response $res)
    {
        $this->chalk->module('core')->publish();

        $this->notify("Items published successfully", 'positive');
        if (isset($this->model->redirect)) {
            return $res->redirect($this->model->redirect);
        } else {
            return $res->redirect($this->url([], 'core_index', true));
        }       
    }

    public function source(Request $req, Response $res)
    {
        $req->view->source = $wrap = $this->em->wrap(
            $source = new \Chalk\Core\Backend\Model\Index\Source()
        );

        $wrap->graphFromArray($req->bodyParams());
        if (!$req->post) {
            return;
        }

        $req->data->code = $source->codeRaw;
    }

    public function select(Request $req, Response $res)
    {
        $select = $this->hook->fire('core_select', new Nav(
            $req,
            $this->url,
            $this->user,
            $this->model->filtersInfo
        ));

        $items = $select->children('root');
        if (!count($items)) {
            throw new \Exception('No route for redirection');
        }
        $item = current($items);
        $url = $item['url'];
        $url->queryParams([
            'mode'        => "select-{$this->model->mode}",
            'filtersList' => $this->model->filtersList,
            'selectedUrl' => $this->model->selectedUrl,
        ]);
        return $res->redirect($url);
    }

    public function forbidden(Request $req, Response $res)
    {
        return $res
            ->status(403)
            ->html($this->view->render('error/forbidden', [
                'req' => $req,
                'res' => $res,
            ] + (array) $req->view, 'core'));
    }
    
    public function ping(Request $req, Response $res)
    {
        return true;
    }
}