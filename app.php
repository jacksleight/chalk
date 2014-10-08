<?php
use Chalk\Chalk,
    Chalk\Core,
    Coast\App\Controller, 
    Coast\App\Image,
    Coast\App\Router, 
    Coast\App\UrlResolver,
    Coast\App\View,
    Coast\Config,
    Coast\Path,
    Coast\Request, 
    Coast\Response, 
    Coast\Url,
    Toast\App\Locale;

$app = (new Chalk(__DIR__, $config->envs))
    ->path(new Path("{$config->path}"))
    ->param('root',   $app)
    ->param('config', $config);

$app->param('controller', new Controller())
    ->param('router', new Router([
        'target' => $app->controller,
    ]))
    ->param('view', new View())
    ->param('memcached', $app->import($app->file('app/memcached.php')))
    ->param('em', $app->import($app->file('app/doctrine.php')))
    ->param('swift', $app->import($app->file('app/swift.php')))
    ->param('url', new UrlResolver([
        'baseUrl' => new Url("{$config->baseUrl}{$app->path()}/"),
        'baseDir' => $app->dir('public'),
        'router'  => $app->router,
    ]))
    ->param('rootUrl', new UrlResolver([
        'baseUrl' => new Url("{$config->baseUrl}"),
        'baseDir' => $app->root->dir(),
    ]))
    ->param('image', new Image([
        'baseDir'           => $app->root->dir('public/data/file'),
        'outputDir'         => $app->root->dir('public/data/image', true),
        'urlResolver'       => $app->url,
        'outputUrlResolver' => $app->rootUrl,
        'transforms'        => $app->import($app->file('app/transforms.php'))
    ]))
    ->param('locale', new Locale([
        'cookie'  => 'locale',
        'locales' => [
            'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
        ]]));


$app->module('core', new Core());
$app->module('root', $app->root);

$app->styles($config->styles);

$app->executable($app->image)
    ->executable($app->locale)
    ->executable($app->router);

$app->notFoundHandler(function(Request $req, Response $res) {
    return $res
        ->status(404)
        ->html($this->view->render('error/not-found', ['req' => $req, 'res' => $res]));
});
if (!$app->isDebug()) {
    $app->errorHandler(function(Request $req, Response $res, Exception $e) {
        return $res
            ->status(500)
            ->html($this->view->render('error/index', ['req' => $req, 'res' => $res, 'e' => $e]));
    });
}

\Chalk\Core\File::baseDir($app->root->dir('public/data/file', true));
\Chalk\Core\File::mimeTypes($app->import($app->file('app/mime-types.php')));

$app->import($app->file('app/routes.php'));

return $app;