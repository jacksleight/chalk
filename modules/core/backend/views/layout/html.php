<?php
$suffix = $this->chalk->isDebug() ? '' : '.min';
$lang   = 'en-GB';
$title  = (isset($title) 
    ? $title . ' – '
    : null) . 'Chalk';
?>
<!DOCTYPE html>
<html class="no-js <?= isset($htmlClass) ? $htmlClass : null ?>" lang="<?= $lang ?>">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="apple-mobile-web-app-title" content="Foundation">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $this->frontendUrl->file("vendor/jacksleight/chalk/public/assets/styles/styles{$suffix}.css") ?>">
</head>
<body class="<?= isset($class) ? $class : '' ?>">
    <?= $this->content('body') ?>
    <script>
        var Chalk = <?= json_encode([
            'baseUrl'       => (string) $this->url->baseUrl(),
            'rootBaseUrl'   => (string) $this->frontendUrl->baseUrl(),
            'pingUrl'       => (string) $this->url([
                'controller' => 'index',
                'action'     => 'ping',
            ], 'core_index', true),
            'prefsUrl'       => (string) $this->url([
                'controller' => 'index',
                'action'     => 'prefs',
            ], 'core_index', true),
            'selectUrl'     => (string) $this->url([
                'controller' => 'index',
                'action'     => 'select',
            ], 'core_select', true),
            'widgetUrl'     => (string) $this->url([
                'action' => 'update',
            ], 'core_widget', true) . $this->url->query([
                'method' => '{method}',
                'type'   => '{type}',
                'state'  => '{state}',
            ], true),
            'sourceUrl'     => (string) $this->url([
                'controller' => 'index',
                'action'     => 'source',
            ], 'core_index', true),
            'contentName'   => Chalk\Chalk::info('Chalk\Core\Content')->name,
            'prefs'         => isset($this->user) ? $this->user->prefs() : [],
            'styles'        => $this->chalk->config->styles,
            'notifications' => $this->notify->notifications(),
            'widgets'       => $this->hook->fire('core_info/core_widget', new Chalk\Info())->sort()->items(),
            'editorContent' => [
                'src'     => (string) $this->frontendUrl->file("vendor/jacksleight/chalk/public/assets/scripts/editor-content{$suffix}.js"),
                'loaded'  => false,
                'loading' => false,
                'queue'   => [],
            ],
            'editorCode' => [
                'src'     => (string) $this->frontendUrl->file("vendor/jacksleight/chalk/public/assets/scripts/editor-code{$suffix}.js"),
                'loaded'  => false,
                'loading' => false,
                'queue'   => [],
            ],
        ]) ?>;
        Chalk.execute = [];
        Chalk.domReady = function(a,b,c){b=document,c='addEventListener';b[c]?b[c]('DOMContentLoaded',a):window.attachEvent('onload',a)}
        Chalk.domReady(function() {
            var script = document.createElement('script');
            script.src = '<?= $this->frontendUrl->file("vendor/jacksleight/chalk/public/assets/scripts/scripts{$suffix}.js") ?>';
            document.head.appendChild(script);
        });
    </script>
    <script type="x-tmpl-mustache" class="modal-template">
        <div class="modal hideable hideable-hidden">
            <div class="modal-inner">
                <button class="modal-button modal-close hideable hideable-hidden btn btn-block btn-light btn-icon btn-collapse icon-cross" type="button"><span>Close</span></button>
                <div class="modal-content hideable hideable-hidden"></div>
            </div>
            <div class="modal-loader hideable hideable-hidden"></div>
        </div>
    </script>
    <?= $this->content('foot') ?>
</body>
</html>