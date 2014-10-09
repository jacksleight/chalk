<?php
use Chalk\Frontend,
    Coast\App\Controller, 
    Coast\App\Router, 
    Coast\App\UrlResolver,
    Coast\App\View,
    Coast\Request, 
    Coast\Response, 
    Coast\Url;

$app = (new Frontend($app->root->dir(), $app->config->envs))
	->param('root',   $app->root)
    ->param('chalk',  $app)
	->param('em',     $app->em)
	->param('config', $app->config);

$app
    ->param('controller', new Controller())
    ->param('router', new Router([
        // 'target' => $app->controller,
    ]))
    ->param('url', new UrlResolver([
        'baseUrl' => new Url("{$app->config->baseUrl}"),
        'baseDir' => $app->chalk->dir(),
    ]));

return $app;