<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

use Chalk\Chalk;
use Chalk\Core\File;
use Chalk\Core\Module as Core;
use Chalk\Frontend;
use Chalk\Frontend\UrlResolver as FrontendUrlResolver;
use Chalk\Notifier;
use Coast\Controller;
use Coast\Image;
use Coast\Router;
use Coast\UrlResolver;
use Coast\View;
use Coast\Config;
use Coast\Path;
use Coast\Request;
use Coast\Response;
use Coast\Url;

$frontend = (new Frontend($app->dir(), $config->envs))
    ->param('root', $app)
    ->param('config', $config);
$frontend
    ->param('router', new Router())
    ->param('controller', new Controller())
    ->param('view', $config->view)
    ->param('url', new FrontendUrlResolver([
        'baseUrl' => new Url("{$config->frontBaseUrl}"),
        'baseDir' => $app->dir(),
        'router'  => $frontend->router,
    ]));

$chalk = (new Chalk(__DIR__, $config->envs))
    ->param('root', $app)
    ->param('config', $config);
$chalk
    ->param('frontend', $frontend)
    ->param('notify', new Notifier())
    ->param('controller', new Controller())
    ->param('router', new Router([
        'target' => $chalk->controller,
        'routes' => $chalk->load('app/routes.php'),
    ]))
    ->param('view', new View())
    ->param('cache', $chalk->load($chalk->file('app/cache.php')))
    ->param('em', $chalk->load($chalk->file('app/doctrine.php')))
    ->param('swift', $chalk->load($chalk->file('app/swift.php')))
    ->param('url', new UrlResolver([
        'baseUrl' => new Url("{$config->backBaseUrl}"),
        'baseDir' => $chalk->dir('public'),
        'router'  => $chalk->router,
    ]))
    ->param('image', new Image([
        'baseDir'           => $config->publicDataDir->dir('file'),
        'outputDir'         => $config->publicDataDir->dir('image'),
        'urlResolver'       => $chalk->url,
        'outputUrlResolver' => $frontend->url,
        'transforms'        => $chalk->load($chalk->file('app/transforms.php'))
    ]))
    ->notFoundHandler(function(Request $req, Response $res) {
        return $res
            ->status(404)
            ->html($this->view->render('error/not-found', ['req' => $req, 'res' => $res]));
    })
    ->errorHandler(function(Request $req, Response $res, Exception $e) {
        if ($this->isDebug()) {
            throw $e;
        }
        return $res
            ->status(500)
            ->html($this->view->render('error/index', ['req' => $req, 'res' => $res, 'e' => $e]));
    });
$chalk
    ->module('core', new Core());
    
$frontend
    ->param('cache', $chalk->cache)
    ->param('em', $chalk->em);

$chalk
    ->executable($chalk->image)
    ->executable($chalk->router);

File::baseDir($config->publicDataDir->dir('file'));
File::mimeTypes($chalk->load($chalk->file('app/mime-types.php')));

return $chalk;