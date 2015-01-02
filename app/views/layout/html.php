<?php
$mode   = $this->app->isDebug() ? '' : '.min';
$lang   = 'en-GB';
$title  = (isset($title) 
    ? $title . ' – '
    : null) . 'Chalk';
?>
<!DOCTYPE html>
<html class="no-js" lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="apple-mobile-web-app-title" content="Foundation">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/styles/styles{$mode}.css") ?>">
</head>
<body class="<?= isset($class) ? $class : '' ?>">
    <?= $this->content() ?>
    <script>
        var Chalk = <?= json_encode([
            'baseUrl'       => (string) $this->url->baseUrl(),
            'rootBaseUrl'   => (string) $this->frontend->url->baseUrl(),
            'browseUrl'     => (string) $this->url([
                'action' => 'browse',
                'entity' => '{entity}',
            ], 'content', true),
            'widgetUrl'     => (string) $this->url([
                'action' => 'edit',
                'entity' => '{entity}',
            ], 'widget', true),
            'sourceUrl'     => (string) $this->url([
                'controller' => 'index',
                'action'     => 'source',
            ], 'index', true),
            'contentName'   => \Chalk\Chalk::info('Chalk\Core\Content')->name,
            'prefs'         => isset($req->user) ? $req->user->prefs() : [],
            'styles'        => $this->config->styles,
            'notifications' => $this->notify->notifications(),
            'widgets'       => array_map('\Chalk\Chalk::info', $this->app->fire('Chalk\Core\Event\ListWidgets')->widgets()),
            'editorContent' => [
                'src'     => (string) $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/scripts/editor-content{$mode}.js"),
                'loaded'  => false,
                'loading' => false,
                'queue'   => [],
            ],
            'editorCode' => [
                'src'     => (string) $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/scripts/editor-code{$mode}.js"),
                'loaded'  => false,
                'loading' => false,
                'queue'   => [],
            ],
        ]) ?>;
        Chalk.DOMReady = function(a,b,c){b=document,c='addEventListener';b[c]?b[c]('DOMContentLoaded',a):window.attachEvent('onload',a)}
        Chalk.DOMReady(function() {
            var script = document.createElement('script');
            script.src = '<?= $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/scripts/scripts{$mode}.js") ?>';
            document.head.appendChild(script);
        });
    </script>
    <script type="x-tmpl-mustache" class="modal-template">
        <div class="modal hideable hideable-hidden">
            <div class="modal-content hideable hideable-hidden"></div>
            <div class="modal-loader hideable hideable-hidden"></div>
        </div>
    </script>    
</body>
</html>