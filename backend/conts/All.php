<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Backend\Controller;

use Chalk\Chalk;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;
use Chalk\InfoList;
use Chalk\Core\NavList;

class All extends Action
{
    public function preDispatch(Request $req, Response $res)
    {
        $this->navList     = $this->hook->fire('core_navList', new NavList());
        $this->contentList = $this->hook->fire('core_contentList', new InfoList());
        $this->widgetList  = $this->hook->fire('core_widgetList', new InfoList());

        $session = $this->session->data('__Chalk');
        if (!isset($session->user) && $req->controller !== 'auth') {
            return $res->redirect($this->url([], 'core_login', true));
        }

        $req->view = (object) [];

        if ($req->controller == 'auth') {
            return;
        }

        $req->user = $this->em('Chalk\Core\User')->id($session->user);
        if (!isset($req->user)) {
            $session->user = null;
            return $res->redirect($this->url([], 'core_login', true));
        }

        $this->em->trackable()->setUser($req->user);
        
        // if ($req->controller != 'index' || $req->action != 'prefs') {
        //  $name   = "query_" . md5(serialize($req->route['params']));
        //  $value  = $req->queryParams();
        //  if (count($value)) {
        //      $req->user->pref($name, $value);
        //      $this->em->flush();
        //  } else {
        //      $value = $req->user->pref($name);
        //      if (isset($value)) {
        //          $req->queryParams($value);
        //      }
        //  }
        // }
    }

    public function postDispatch(Request $req, Response $res)
    {
        $controller = strtolower(str_replace('_', '/', $req->controller));
        $action     = strtolower(str_replace('_', '-', $req->action));
        $path = isset($req->view->path)
            ? $req->view->path
            : "{$controller}/{$action}";

        // if ($req->isAjax()) {
        //  $notifications = $this->notify->notifications(false);
        //  $negative = false;
        //  foreach ($notifications as $notification) {
        //      if ($notification[1] == 'negative') {
        //          $negative = true;
        //          break;
        //      }
        //  }
        //  return $res->json([
        //      'notifications' => $this->notify->notifications(),
        //      'html' => $negative
        //          ? $res->html($this->view->render($path, [
        //              'req' => $req,
        //              'res' => $res,
        //          ] + (array) $req->view))
        //          : null]);
        // }

        return $res->html($this->view->render($path, [
            'req' => $req,
            'res' => $res,
        ] + (array) $req->view, isset($req->group) ? $req->group : 'core'));
    }
}