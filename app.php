<?php
use Chalk\Chalk;
use Chalk\Core\File;
use Chalk\Frontend;
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
use Toast\App\Locale;

$chalk = (new Chalk(__DIR__, $config->envs))
    ->path(new Path("{$config->path}"))
    ->param('root', $app)
    ->param('config', $config);

$frontend = (new Frontend($app->dir(), $config->envs))
    ->param('root', $app)
    ->param('config', $config);

$chalk
    ->param('frontend', $frontend)
    ->param('controller', new Controller())
    ->param('router', new Router([
        'target' => $chalk->controller,
    ]))
    ->param('view', new View())
    ->param('memcached', $chalk->import($chalk->file('app/memcached.php')))
    ->param('em', $chalk->import($chalk->file('app/doctrine.php')))
    ->param('swift', $chalk->import($chalk->file('app/swift.php')))
    ->param('url', new UrlResolver([
        'baseUrl' => new Url("{$config->baseUrl}{$chalk->path()}/"),
        'baseDir' => $chalk->dir('public'),
        'router'  => $chalk->router,
    ]))
    ->param('image', new Image([
        'baseDir'           => $app->dir('public/data/file'),
        'outputDir'         => $app->dir('public/data/image', true),
        'urlResolver'       => $chalk->url,
        'outputUrlResolver' => $frontend->url,
        'transforms'        => $chalk->import($chalk->file('app/transforms.php'))
    ]))
    ->param('locale', new Locale([
        'cookie'  => 'locale',
        'locales' => [
            'en-GB' => 'en-GB@timezone=Europe/London;currency=GBP',
        ]
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
    ->param('controller', new Controller())
    ->param('em', $chalk->em)
    ->param('view', $config->view)
    ->param('router', new Router([
        // 'target' => $chalk->controller,
    ]))
    ->param('url', new UrlResolver([
        'baseUrl' => new Url("{$config->baseUrl}"),
        'baseDir' => $app->dir(),
    ]));

$chalk
    ->executable($chalk->image)
    ->executable($chalk->locale)
    ->executable($chalk->router);

File::baseDir($app->dir('public/data/file', true));
File::mimeTypes($chalk->import($chalk->file('app/mime-types.php')));

$chalk->import($chalk->file('app/routes.php'));

return $chalk;