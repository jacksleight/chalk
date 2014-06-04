<?php
use Coast\App\Controller, 
    Coast\App\Request, 
    Coast\App\Response, 
    Coast\App\Router, 
    Coast\App\UrlResolver,
    Coast\App\View,
    Coast\Config,
    Coast\Path,
    Coast\Url,
    Ayre\Core,
    Toast\App\Image,
    Toast\App\Locale;


//var_dump(json_encode(['ayre' => ['url_content', 34]]));
//die;


$root = $app;
$app  = new Ayre(__DIR__, $config->envs);

$app->path(new Path("{$config->path}"))
    ->set('root',       $root)
    ->set('config',     $config)
    ->set('controller', new Controller())
    ->set('router',     new Router($app->controller))
    ->set('view',       new View())
    ->set('memcached',  $app->import($app->file('init/memcached.php')))
    ->set('em',         $app->import($app->file('init/doctrine.php')))
    ->set('swift',      $app->import($app->file('init/swift.php')))
    ->set('url',        new UrlResolver(
            new Url("{$config->baseUrl}{$app->path()}/"),
            $app->dir(),
            $app->router) )
    ->set('rootUrl',    new UrlResolver(
            new Url("{$config->baseUrl}"),
            $root->dir()) )
    ->set('image',      (new Image(
            $root->dir('public/data/file'),
            $root->dir('public/data/image', true),
            $app->url,
            $app->import($app->file('init/transforms.php'))))
        ->outputUrlResolver($app->rootUrl))
    ->set('locale', new Locale([
            'cookie'  => 'locale',
            'locales' => [
                'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
            ]]));

$app->module(new Core());

$app->add($app->image)
    ->add($app->locale)
    ->add($app->router);

$app->notFoundHandler(function(Request $req, Response $res) {
    return $res
        ->status(404)
        ->html($this->view->render('error/not-found'));
});
if (!$app->isDebug()) {
    $app->errorHandler(function(Request $req, Response $res, Exception $e) {
        return $res
            ->status(500)
            ->html($this->view->render('error/index', array('e' => $e)));
    });
}

\Ayre\Core\File::baseDir($root->dir('public/data/file', true));
\Ayre\Core\File::mimeTypes($app->import($app->file('init/mime-types.php')));

$app->import($app->file('init/routes.php'));

return $app;