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
use Chalk\Nav;

class All extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $session = $this->session->data('__Chalk\Backend');

        $this->module = $this->chalk->module(\Coast\str_camel_lower($req->dispatch['group']));
        $this->domain = $this->em('core_domain')->id(1, [], [], false);
        $this->user   = isset($session->user) ? $session->user : null;
        $this->model  = $this->_model($req);

        $req->data = (object) [];
        $req->view = (object) [];
        $req->view->model = $this->model;

        if ($req->dispatch['controller'] == 'auth') {
            return;
        }
        if (!isset($this->user)) {
            return $res->redirect($this->_login($req));
        }

        $this->nav = $this->hook->fire('core_nav', new Nav(
            $req,
            $this->url,
            $this->user,
            $this->model->filtersInfo
        ));

        $userRef = $this->em->ref('core_user', $this->user->id);
        $this->em->listener('core_trackable')->setUser($userRef);

        // ========

        // $name   = "query_" . md5($req->path);
        // $params = $req->queryParams();
        // if (!count($params)) {
        //     $params = $this->user->pref($name);
        //     if (isset($params)) {
        //         return $res->redirect($this->url->query($params));
        //     }
        // } else if (isset($params['remember'])) {
        //     $fields = explode(',', $params['remember']);
        //     $this->user->pref($name, \Coast\array_intersect_key($params, $fields));
        //     $this->em->flush();
        // }
    }

    public function postDispatch(Request $req, Response $res)
    {
        $controller = strtolower($req->dispatch['controller']);
        $action     = strtolower(\Coast\str_camel_split($req->dispatch['action'], '-'));
        $group      = \Coast\str_camel_lower($req->dispatch['group']);
        $path       = isset($req->view->path)
            ? $req->view->path
            : "{$controller}/{$action}";

        $isJson   = $req->isAjax() && count((array) $req->data);
        $isNotify = $req->isAjax() && !isset($req->data->redirect) && !isset($req->data->reload);

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
            ] + (array) $req->view, $group));
    }

    protected function _modelClass(Request $req)
    {
        $controller = implode('\\', array_map('\Coast\str_camel_upper', explode('/', $req->dispatch['controller'])));
        $default    = "Backend\\Controller\\{$controller}";
        $default    = $this->module->nspace($default);
        $classes    = array_merge(
            [$default],
            array_values(class_parents($default))
        );
        foreach ($classes as $class) {
            $class = str_replace('\\Controller\\', '\\Model\\', $class)
                . '\\' . \Coast\str_camel_upper($req->dispatch['action']);
            if (class_exists($class)) {
                return $class;
                break;
            }
        }
        return 'Chalk\Core\Backend\Model';
    }

    protected function _model(Request $req)
    {
        $class = $this->_modelClass($req);
        $model = new $class();
        $model = $this->em->wrap($model);

        $model->graphFromArray($req->queryParams());
        $model->filtersInfo = $this->_info($model->filters);

        return $model;
    }

    protected function _info($data)
    {
        if (!is_array($data)) {
            $info = $this->hook->fire($data, new \Chalk\Info());
        } else {
            $info = new \Chalk\Info();
            foreach ($data as $name => $subs) {
                $info->item($name, [
                    'subs' => $subs,
                ]);
            }
        }
        return $info;
    }

    protected function _login(Request $req)
    {
        $query = [];
        if ($req->dispatch['controller'] != 'index' || $req->dispatch['action'] != 'index') {
            $query['redirect'] = $req->url()->toPart(Url::PART_PATH, true)->toString();
        }
        return $this->url([], 'core_login', true) . $this->url->query($query, true);
    }
}