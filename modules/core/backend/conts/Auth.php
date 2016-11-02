<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
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
        if (!$wrap->graphIsValid()) {
            return;
        }

        $user = $this->em('core_user')->one(['emailAddress' => $login->emailAddress]);
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

        // $this->session->regenerate();
        $session = $this->session->data('__Chalk\Backend');
        $session->user = $user->id;

        return $res->redirect($req->redirect
            ? $req->redirect
            : $this->url([], 'core_index', true));
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
        if (!$wrap->graphIsValid()) {
            return;
        }

        $user = $this->em('core_user')->one(['emailAddress' => $passwordRequest->emailAddress]);
        if (!isset($user)) {
            $passwordRequest->addError('emailAddress', 'Sorry, that account could not be found');
            return;
        }

        $user->token     = sha1(uniqid(mt_rand(), true));
        $user->tokenDate = new \DateTime('+24 hour');
        $this->em->flush();

        $this->swift->send(\Swift_Message::newInstance()
            ->setSubject("{$this->domain->label} CMS Password Reset")
            ->setTo($user->emailAddress)
            ->setFrom($this->domain->emailAddress)
            ->setBody((string) $this->view->render('email/password-request', [
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

        $req->view->passwordReset = $wrap = $this->em->wrap(
            $passwordReset = new \Chalk\Core\Model\PasswordReset()
        );

        if (!$req->isPost()) {
            return;
        }

        $wrap->graphFromArray($req->bodyParams());
        if (!$wrap->graphIsValid()) {
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
        // $this->session->regenerate();
        $session = $this->session->data('__Chalk\Backend');
        $session->user = null;

        return $res->redirect($this->url([], 'core_login', true));
    }
}