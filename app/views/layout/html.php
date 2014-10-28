<?php
$code   = $this->app->isDebug() ? '' : '.min';
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
    <link rel="stylesheet" href="<?= $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/styles/styles{$code}.css") ?>">
    <link rel="shortcut icon" href="<?= $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/images/favicon.ico") ?>">
    <link rel="apple-touch-icon-precomposed" href="<?= $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/images/touch-icon-precomposed.png") ?>">
</head>
<body class="<?= isset($class) ? $class : '' ?>">
    <?= $content ?>
    <script>
        var Chalk = <?= json_encode([
            'baseUrl'       => (string) $this->url->baseUrl(),
            'rootBaseUrl'   => (string) $this->frontend->url->baseUrl(),
            'selectUrl'     => (string) $this->url([
                'action' => 'select',
                'entity' => '{entity}',
            ], 'content', true),
            'widgetUrl'     => (string) $this->url([
                'action' => 'edit',
                'entity' => '{entity}',
            ], 'widget', true),
            'contentName'   => \Chalk\Chalk::info('Chalk\Core\Content')->name,
            'prefs'         => $req->user->prefs(),
            'styles'        => $this->config->styles,
            'widgets'       => array_map('\Chalk\Chalk::info', $this->app->fire('Chalk\Core\Event\ListWidgets')->widgets()),
        ]) ?>;
    </script>
    <script type="x-tmpl-mustache" class="modal-template">
        <div class="modal hideable hideable-hidden">
            <div class="modal-content hideable hideable-hidden"></div>
            <div class="modal-loader hideable hideable-hidden"></div>
        </div>
    </script>
    <script src="<?= $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/scripts/scripts{$code}.js") ?>"></script>
    <script src="<?= $this->frontend->url->file("vendor/jacksleight/chalk/public/assets/scripts/editor{$code}.js") ?>"></script>
    <script>
        FastClick.attach(document.body);
        Chalk.initialize(document.body);
    </script>
</body>
</html>