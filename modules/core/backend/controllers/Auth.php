<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Core\Backend\Controller;

use Coast\Controller\Action,
    Coast\Request,
    Coast\Response;

class Auth extends Action
{
    public function login(Request $req, Response $res)
    {
        if (!$req->isPost()) {
            return;
        }

        $this->model->graphFromArray($req->bodyParams());
        if (!$this->model->graphIsValid()) {
            return;
        }

        $user = $this->em('core_user')->one(['emailAddress' => $this->model->emailAddress]);
        if (!isset($user)) {
            $this->model->password = null;
            $this->model->addError('emailAddress', 'login');
            return;
        } else if (!$user->verifyPassword($this->model->password)) {
            $this->model->password = null;
            $this->model->addError('emailAddress', 'login');
            return;
        }

        $user->loginDate = new \Carbon\Carbon();
        $this->em->flush();

        // $this->session->regenerate();
        $session = $this->session->data('__Chalk\Backend');
        $session->user = $user;

        if (isset($this->model->redirect)) {
            return $res->redirect($this->model->redirect);
        } else {
            return $res->redirect($this->url([], 'core_index', true));
        } 
    }

    public function passwordRequest(Request $req, Response $res)
    {
        if (!$req->isPost()) {
            return;
        }

        $this->model->graphFromArray($req->bodyParams());
        if (!$this->model->graphIsValid()) {
            return;
        }

        $user = $this->em('core_user')->one(['emailAddress' => $this->model->emailAddress]);
        if (!isset($user)) {
            $this->model->addError('emailAddress', 'Sorry, that account could not be found');
            return;
        }

        $user->token     = sha1(uniqid(mt_rand(), true));
        $user->tokenDate = new \DateTime('+24 hour');
        $this->em->flush();

        $this->swift->send((new \Swift_Message())
            ->setSubject("{$this->domain->label} Password Reset")
            ->setTo($user->emailAddress)
            ->setFrom($this->domain->emailAddress)
            ->setBody((string) $this->view->render('email/password-request', [
                'domain' => $this->domain,
                'user' => $user,
            ], 'core')));

        $this->notify('Please check your email for password reset instructions');
        return $res->redirect($this->url([], 'core_login', true));
    }

    public function passwordReset(Request $req, Response $res)
    {
        $user = $this->em('core_user')->one(['token' => $req->token]);
        if (!isset($user)) {
          $this->notify('Sorry, your password reset link has expired, please request a new one', 'negative');
            return $res->redirect($this->url([], 'core_passwordRequest', true));
        }

        if (!$req->isPost()) {
            return;
        }

        $this->model->graphFromArray($req->bodyParams());
        if (!$this->model->graphIsValid()) {
            return;
        }

        $user->passwordPlain = $this->model->password;
        $user->token         = null;
        $user->tokenDate     = null;
        $this->em->flush();

        $this->notify('Your password has been reset successfully', 'positive');
        return $res->redirect($this->url([], 'core_login', true));
    }

    public function logout(Request $req, Response $res)
    {
        // $this->session->regenerate();
        $session = $this->session->data('__Chalk\Backend');
        $session->user = null;

        return $res->redirect($this->url([], 'core_login', true));
    }
    
    public function jump(Request $req, Response $res)
    {}
}