<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Url;
use Coast\Request;
use Coast\Response;
use Chalk\Info;
use Chalk\Core\Nav;

class All extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $session   = $this->session->data('__Chalk\Backend');
        $req->user = isset($session->user) ? $session->user : null;

        $this->app->module = $module = $this->chalk->module($req->group);
        
        $req->data = (object) [];
        $req->view = (object) [];

        if ($req->controller == 'auth') {
            return;
        }

        if (!isset($req->user)) {
            $query = [];
            if ($req->controller != 'index' || $req->action != 'index') {
                $query['redirect'] = (string) $req->url()->toPart(Url::PART_PATH, true);
            }
            return $res->redirect($this->url([], 'core_login', true) . $this->url->query($query, true));
        }

        $this->domain = $this->em('core_domain')->id(1, [], [], false);
        $this->em->listener('core_trackable')->setUser($this->em->reference('core_user', $req->user->id));

        // ========
       
        $class = $module->nspace("Backend\\Controller\\" . ucfirst($req->controller));
        $parents = array_merge(
            [$class],
            array_values(class_parents($class))
        );
        $class = 'Chalk\Core\Backend\Model';
        foreach ($parents as $parent) {
            $check = str_replace('\\Controller\\', '\\Model\\', $parent) . '\\' . ucfirst($req->action);
            if (class_exists($check)) {
                $class = $check;
                break;
            }
        }
        $model = new $class();
        $model = $this->em->wrap($model);
        $model->graphFromArray($req->queryParams());
        $req->view->model = $req->model = $model;

        $filters = $model->filters();
        if (!is_array($filters)) {
            $filtersInfo = $this->hook->fire("core_info/{$filters}", new \Chalk\Info());
        } else {
            $filtersInfo = new \Chalk\Info();
            foreach ($filters as $name => $subs) {
                $filtersInfo->item($name, [
                    'subs' => $subs,
                ]);
            }
        }
        $model->filtersInfo = $filtersInfo;

        // ========

        $this->nav    = $this->hook->fire('core_nav', new Nav($this->url, $req, $req->user, $filtersInfo));
        $this->select = $this->hook->fire('core_select', new Nav($this->url, $req, $req->user, $filtersInfo));
        // $this->widgetList  = $this->hook->fire('core_widgetList', new Info());
        // $this->widgetList->sort();

        // ========

        // $name   = "query_" . md5($req->path);
        // $params = $req->queryParams();
        // if (!count($params)) {
        //     $params = $req->user->pref($name);
        //     if (isset($params)) {
        //         return $res->redirect($this->url->query($params));
        //     }
        // } else if (isset($params['remember'])) {
        //     $fields = explode(',', $params['remember']);
        //     $req->user->pref($name, \Coast\array_intersect_key($params, $fields));
        //     $this->em->flush();
        // }
    }

    public function postDispatch(Request $req, Response $res)
    {
        $controller = strtolower(str_replace('_', '/', $req->controller));
        $action     = strtolower(str_replace('_', '-', $req->action));
        $path       = isset($req->view->path)
            ? $req->view->path
            : "{$controller}/{$action}";

        $isJson   = $req->isAjax() && count((array) $req->data);
        $isNotify = $req->isAjax() && !isset($req->data->redirect);

        if ($isNotify) {
            $req->data->notifications = $this->notify->notifications();
        }
        if ($isJson) {
            return $res->json($req->data);
        }

        return $res
            ->header('X-JSON', json_encode($req->data))
            ->html($this->view->render($path, [
                'req' => $req,
                'res' => $res,
            ] + (array) $req->view, $req->group));
    }
}