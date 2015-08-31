<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

use Chalk\Chalk;
use Chalk\Core\File;
use Chalk\Core\Module as Core;
use Chalk\Backend;
use Chalk\Frontend;
use Chalk\Frontend\UrlResolver as FrontendUrlResolver;
use Chalk\Notifier;
use Coast\Controller;
use Coast\Request;
use Coast\Response;
use Coast\Router;
use Coast\Session;
use Coast\Url;
use Coast\UrlResolver;
use Coast\View;

$chalk = (new Chalk(__DIR__, $config->envs))
    ->param('root', $app)
    ->param('config', $config);
$chalk
    ->param('em', $chalk->lazy('app/init/em.php'))
    ->param('cache', $chalk->lazy('app/init/cache.php'))
    ->param('swift', $chalk->lazy('app/init/swift.php'));

$frontend = (new Frontend($app->dir(), $config->envs))
    ->param('chalk', $chalk);
$frontend
    ->param('router', new Router())
    ->param('controller', new Controller())
    ->param('view', $config->view)
    ->param('url', new FrontendUrlResolver([
        'baseUrl' => new Url("{$config->frontBaseUrl}"),
        'baseDir' => $app->dir(),
        'router'  => $frontend->router,
    ]))
    ->param('em', $chalk->em)
    ->param('cache', $chalk->cache)
    ->param('swift', $chalk->swift);
$chalk->param('frontend', $frontend);

$backend = (new Backend(__DIR__, $config->envs))
    ->param('chalk', $chalk)
    ->param('frontend', $frontend);
$backend
    ->param('view', new View())
    ->param('notify', new Notifier())
    ->param('controller', new Controller())
    ->param('session', new Session([
        'expires' => null,
    ]))
    ->param('router', $backend->load('app/init/router.php'))
    ->param('url', new UrlResolver([
        'baseUrl' => new Url("{$config->backBaseUrl}"),
        'baseDir' => $backend->dir('public'),
        'router'  => $backend->router,
    ]))
    ->param('image', $backend->load('app/init/image.php'))
    ->param('em', $chalk->em)
    ->param('cache', $chalk->cache)
    ->param('swift', $chalk->swift)
    ->failureHandler(function(Request $req, Response $res) {
        return $res
            ->status(404)
            ->html($this->view->render('error/not-found', [
                'req' => $req,
                'res' => $res,
            ]));
    })
    ->errorHandler(function(Request $req, Response $res, Exception $e) {
        if ($this->chalk->isDebug()) {
            throw $e;
        }
        return $res
            ->status(500)
            ->html($this->view->render('error/index', [
                'req' => $req,
                'res' => $res,
                'e'   => $e,
            ]));
    });
$chalk->param('backend', $backend);
$frontend->param('backend', $backend);

$backend->executable($backend->session);
$backend->executable($backend->image);
$backend->executable($backend->router);

$chalk
    ->module('core', new Core());

File::baseDir($config->publicDataDir->dir('file'));
File::mimeTypes($chalk->load('app/init/mime-types.php'));

return $chalk;