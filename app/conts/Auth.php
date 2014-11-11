<?php
namespace Chalk\Core\Controller;

use Coast\App\Controller\Action,
    Coast\Request,
    Coast\Response;

class Auth extends Action
{
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

        $user = $this->em('Chalk\Core\User')->fetchByEmailAddress($login->emailAddress);
        if (!isset($user)) {
            $login->password = null;
            $login->addError('emailAddress', 'Sorry, that account could not be found');
            return;
        } else if (!$user->verifyPassword($login->password)) {
            $login->password = null;
            $login->addError('emailAddress', 'Sorry, that account could not be found');
            return;
        }

        $user->loginDate = new \Carbon\Carbon();
        $this->em->flush();

        $session =& $req->session('chalk');
        $session->user = $user->id;

        return $res->redirect($this->url(array(), 'index', true));
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

        $user = $this->em('Chalk\Core\User')->fetchByEmailAddress($passwordRequest->emailAddress);
        if (!isset($user)) {
            $passwordRequest->addError('emailAddress', 'Sorry, that account could not be found');
            return;
        }

        $user->token     = sha1(uniqid(mt_rand(), true));
        $user->tokenDate = new \DateTime('+24 hour');
        $this->em->flush();

        $this->swift->send(\Swift_Message::newInstance()
            ->setSubject("{$this->config->name} CMS Password Reset")
            ->setTo($user->emailAddress)
            ->setFrom($this->config->emailAddress)
            ->setBody((string) $this->view->render('email/password-request', [
                'user' => $user,
            ])));

        $this->notify('Please check your email for password reset instructions');
        return $res->redirect($this->url([], 'login', true));
    }

    public function passwordReset(Request $req, Response $res)
    {
        $user = $this->em('Chalk\Core\User')->fetchByToken($req->token);
        if (!isset($user)) {
          $this->notify('Sorry, your password reset link has expired, please request a new one', 'negative');
            return $res->redirect($this->url([], 'passwordRequest', true));
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
        return $res->redirect($this->url([], 'login', true));
    }

    public function logout(Request $req, Response $res)
    {
        $session =& $req->session('chalk');
        $session->user = null;

        return $res->redirect($this->url([], 'login', true));
    }
}