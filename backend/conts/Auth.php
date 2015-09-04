<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Chalk\Core\Backend\Controller;

use Coast\Controller\Action,
    Coast\Request,
    Coast\Response;

class Auth extends Action
{
    public function about(Request $req, Response $res)
    {}
    
    public function login(Request $req, Response $res)
    {
        $req->view->login = $wrap = $this->em->wrap(
            $login = new \Chalk\Core\Model\Login()
        );

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->isValid()) {
            return;
        }

        $user = $this->em('Chalk\Core\User')->one(['emailAddress' => $login->emailAddress]);
        if (!isset($user)) {
            $login->password = null;
            $login->addError('emailAddress', 'login');
            return;
        } else if (!$user->verifyPassword($login->password)) {
            $login->password = null;
            $login->addError('emailAddress', 'login');
            return;
        }

        $user->loginDate = new \Carbon\Carbon();
        $this->em->flush();

        $session = $this->session->data('__Chalk');
        $session->user = $user->id;

        return $res->redirect($this->url(array(), 'core_index', true));
    }

    public function passwordRequest(Request $req, Response $res)
    {
        $req->view->passwordRequest = $wrap = $this->em->wrap(
            $passwordRequest = new \Chalk\Core\Model\PasswordRequest()
        );

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->isValid()) {
            return;
        }

        $user = $this->em('Chalk\Core\User')->one(['emailAddress' => $passwordRequest->emailAddress]);
        if (!isset($user)) {
            $passwordRequest->addError('emailAddress', 'Sorry, that account could not be found');
            return;
        }

        $user->token     = sha1(uniqid(mt_rand(), true));
        $user->tokenDate = new \DateTime('+24 hour');
        $this->em->flush();

        $this->swift->send(\Swift_Message::newInstance()
            ->setSubject("{$this->chalk->config->name} CMS Password Reset")
            ->setTo($user->emailAddress)
            ->setFrom($this->chalk->config->emailAddress)
            ->setBody((string) $this->view->render('email/password-request', [
                'user' => $user,
            ], 'core')));

        $this->notify('Please check your email for password reset instructions');
        return $res->redirect($this->url([], 'core_login', true));
    }

    public function passwordReset(Request $req, Response $res)
    {
        $user = $this->em('Chalk\Core\User')->one(['token' => $req->token]);
        if (!isset($user)) {
          $this->notify('Sorry, your password reset link has expired, please request a new one', 'negative');
            return $res->redirect($this->url([], 'core_passwordRequest', true));
        }

        $req->view->passwordReset = $wrap = $this->em->wrap(
            $passwordReset = new \Chalk\Core\Model\PasswordReset()
        );

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->isValid()) {
            return;
        }

        $user->passwordPlain = $passwordReset->password;
        $user->token         = null;
        $user->tokenDate     = null;
        $this->em->flush();

        $this->notify('Your password has been reset successfully', 'positive');
        return $res->redirect($this->url([], 'core_login', true));
    }

    public function logout(Request $req, Response $res)
    {
        $session = $this->session->data('__Chalk');
        $session->user = null;

        return $res->redirect($this->url([], 'core_login', true));
    }
}