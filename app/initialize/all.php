<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

use Chalk\Chalk;
use Chalk\Backend;
use Chalk\Backend\Notifier;
use Chalk\Backend\View as BackendView;
use Chalk\Core\File;
use Chalk\Core\Module as Core;
use Chalk\Frontend;
use Chalk\Frontend\Resolver as FrontendResolver;
use Chalk\HookManager;
use Chalk\Parser;
use Coast\Controller;
use Coast\Lazy;
use Coast\Path;
use Coast\Request;
use Coast\Resolver;
use Coast\Response;
use Coast\Router;
use Coast\Url;
use Coast\View;

$app = (new Chalk(__DIR__, $config->envs))
    ->param('root', $app)
    ->param('config', $config);
$app
    ->param('cache', $app->load('_cache.php'))
    ->param('em', $app->load('_em.php'))
    ->param('swift', $app->lazy('_swift.php'))
    ->param('hook', new HookManager())
    ->param('session', $app->config->session)
    ->module(new Core());

$app->load('_funcs.php');

File::baseDir($config->publicDataDir->dir('file'));
File::mimeTypes($app->load('_mime-types.php'));

Toast\Wrapper::$chalk = $app;
Toast\Wrapper::$timezone = $app->config->timezone;

$app->param('backend', $app->lazy(function($vars) {
    $app = $vars['app'];
    if (!$app->frontend instanceof Lazy && !Chalk::isDual()) {
        $e = new \Exception('Frontend already loaded');
        echo "<pre>{$e}</pre>";
        throw $e;
    }
    $backend = (new Backend(__DIR__, $app->config->envs))
        ->param('chalk', $app)
        ->param('frontend', $app->frontend)
        ->param('session', $app->session);
    $backend
        ->param('view', new BackendView())
        ->param('parser', new Parser([
            'isTidy' => true,
        ]))
        ->param('notify', new Notifier())
        ->param('controller', new Controller())
        ->param('router', new Router([
            'target'  => $backend->controller,
            'params'  => [
                'module' => 'core',
            ],
        ]))
        ->param('resolver', new Resolver([
            'baseUrl'   => new Url("{$app->config->backendBaseUrl}"),
            'baseDir'   => $backend->dir('public'),
            'router'    => $backend->router,
            'cacheBust' => function(Url $url, Path $path) {
                $url->queryParam('v', $path->modifyTime()->getTimestamp());
            },
        ]))
        ->param('frontendResolver', new Resolver([
            'baseUrl'   => new Url("{$app->config->frontendBaseUrl}"),
            'baseDir'   => $app->root->dir(),
            'cacheBust' => function(Url $url, Path $path) {
                $url->queryParam('v', $path->modifyTime()->getTimestamp());
            },
        ]))
        ->param('url', $backend->resolver)
        ->param('frontendUrl', $backend->frontendResolver)
        ->param('image', $backend->load('_image.php'))
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
                ], 'core'));
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
                ], 'core'));
        });
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
    if (!$app->backend instanceof Lazy && !Chalk::isDual()) {
        $e = new \Exception('Backend already loaded');
        echo "<pre>{$e}</pre>";
        throw $e;
    }
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
        ->param('parser', new Parser([
            'isTidy' => false,
        ]))
        ->param('resolver', new FrontendResolver([
            'baseUrl'   => new Url("{$app->config->frontendBaseUrl}"),
            'baseDir'   => $app->root->dir(),
            'router'    => $frontend->router,
            'cacheBust' => function(Url $url, Path $path) {
                $url->queryParam('v', $path->modifyTime()->getTimestamp());
            },
        ]))
        ->param('url', $frontend->resolver)
        ->param('hook', new HookManager())
        ->param('em', $app->em)
        ->param('cache', $app->cache)
        ->param('swift', $app->swift);
    $frontend->executable($frontend->router);
    $frontend->executable($frontend->parser);
    $app->param('frontend', $frontend);
    foreach ($app->modules() as $module) {
        $module->frontendInit();
    }
    return $frontend;
}));

return $app;