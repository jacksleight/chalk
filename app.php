<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

use Chalk\Chalk;
use Chalk\Core\File;
use Chalk\Frontend;
use Chalk\Frontend\UrlResolver as FrontendUrlResolver;
use Chalk\Notifier;
use Coast\App\Controller;
use Coast\App\Image;
use Coast\App\Router;
use Coast\App\UrlResolver;
use Coast\App\View;
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
        'baseUrl' => new Url("{$config->baseUrl}"),
        'baseDir' => $app->dir(),
        'router'  => $frontend->router,
    ]));

$chalk = (new Chalk(__DIR__, $config->envs))
    ->path(new Path("{$config->path}"))
    ->param('root', $app)
    ->param('config', $config);
$chalk
    ->param('frontend', $frontend)
    ->param('notify', new Notifier())
    ->param('controller', new Controller())
    ->param('router', new Router([
        'target' => $chalk->controller,
    ]))
    ->param('view', new View())
    ->param('cache', $chalk->import($chalk->file('app/cache.php')))
    ->param('em', $chalk->import($chalk->file('app/doctrine.php')))
    ->param('swift', $chalk->import($chalk->file('app/swift.php')))
    ->param('url', new UrlResolver([
        'baseUrl' => new Url("{$config->baseUrl}{$chalk->path()}/"),
        'baseDir' => $chalk->dir('public'),
        'router'  => $chalk->router,
    ]))
    ->param('image', new Image([
        'baseDir'           => $config->fileBaseDir,
        'outputDir'         => $config->imageOutputDir,
        'urlResolver'       => $chalk->url,
        'outputUrlResolver' => $frontend->url,
        'transforms'        => $chalk->import($chalk->file('app/transforms.php'))
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
    
$frontend
    ->param('cache', $chalk->cache)
    ->param('em', $chalk->em);

$chalk
    ->executable($chalk->image)
    ->executable($chalk->router);

File::baseDir($config->fileBaseDir);
File::mimeTypes($chalk->import($chalk->file('app/mime-types.php')));

$chalk->import($chalk->file('app/routes.php'));

return $chalk;