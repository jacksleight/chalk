<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\Chalk;
use Chalk\Core\File;
use Chalk\Core\Module as Core;
use Chalk\Backend;
use Chalk\Backend\NavManager;
use Chalk\Frontend;
use Chalk\Frontend\UrlResolver as FrontendUrlResolver;
use Chalk\HookManager;
use Chalk\Notifier;
use Coast\Controller;
use Coast\Request;
use Coast\Response;
use Coast\Router;
use Coast\Session;
use Coast\Url;
use Coast\UrlResolver;
use Coast\View;

$app = (new Chalk(__DIR__, $config->envs))
    ->param('root', $app)
    ->param('config', $config);
$app
    ->param('em', $app->lazy('app/init/em.php'))
    ->param('cache', $app->lazy('app/init/cache.php'))
    ->param('swift', $app->lazy('app/init/swift.php'))
    ->param('hook', new HookManager())
    ->param('session', new Session([
        'expires' => null,
        'name'    => '__Chalk__session',
        'path'    => (new Url("{$app->config->frontendBaseUrl}"))->path(),
    ]))
    ->module(new Core());

File::baseDir($config->publicDataDir->dir('file'));
File::mimeTypes($app->load('app/init/mime-types.php'));

Toast\Wrapper::$timezone = $app->config->timezone;

$app->param('backend', $app->lazy(function($vars) {
    $app = $vars['app'];
    $backend = (new Backend(__DIR__, $app->config->envs))
        ->param('chalk', $app)
        ->param('frontend', $app->frontend)
        ->param('session', $app->session);
    $backend
        ->param('view', new View())
        ->param('notify', new Notifier())
        ->param('controller', new Controller())
        ->param('router', new Router([
            'target'  => $backend->controller,
            'prefix'  => '{module}?',
            'params'  => [
                'module' => 'core',
            ],
        ]))
        ->param('url', new UrlResolver([
            'baseUrl' => new Url("{$app->config->backendBaseUrl}"),
            'baseDir' => $backend->dir('public'),
            'router'  => $backend->router,
        ]))
        ->param('image', $backend->load('app/init/image.php'))
        ->param('hook', new HookManager())
        ->param('em', $app->em)
        ->param('cache', $app->cache)
        ->param('swift', $app->swift)
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
    $backend->executable($backend->session);
    $backend->executable($backend->image);
    $backend->executable($backend->router);
    $app->param('backend', $backend);
    foreach ($app->modules() as $module) {
        $module->backendInit();
    }
    return $backend;
}));

$app->param('frontend', $app->lazy(function($vars) {
    $app = $vars['app'];
    $frontend = (new Frontend($app->root->dir(), $app->config->envs))
        ->param('chalk', $app)
        ->param('backend', $app->backend)
        ->param('session', $app->session);
    $frontend
        ->param('controller', new Controller())
        ->param('router', new Router([
            'target'  => $frontend->controller,
        ]))
        ->param('view', $app->config->view)
        ->param('url', new FrontendUrlResolver([
            'baseUrl' => new Url("{$app->config->frontendBaseUrl}"),
            'baseDir' => $app->root->dir(),
            'router'  => $frontend->router,
        ]))
        ->param('hook', new HookManager())
        ->param('em', $app->em)
        ->param('cache', $app->cache)
        ->param('swift', $app->swift);
    $frontend->executable($frontend->session);
    $frontend->executable($frontend->router);
    $app->param('frontend', $frontend);
    foreach ($app->modules() as $module) {
        $module->frontendInit();
    }
    return $frontend;
}));

return $app;