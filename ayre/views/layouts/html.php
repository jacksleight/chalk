<?php
$code	= $this->app->isDebug() ? '' : '.min';
$lang	= $this->locale->getLanguage() . '-' . $this->locale->getRegion();
$title	= (isset($title) 
	? $title . ' – '
	: null) . 'Ayre';
?>
<!DOCTYPE html>
<html class="no-js" lang="<?= $lang ?>">
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>
	<meta name="apple-mobile-web-app-title" content="Foundation">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='http://fonts.googleapis.com/css?family=Raleway:300,500' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?= $this->rootUrl->file("ayre/assets/build/styles{$code}.css") ?>">
	<link rel="shortcut icon" href="<?= $this->rootUrl->file("ayre/assets/images/favicon.ico") ?>">
	<link rel="apple-touch-icon-precomposed" href="<?= $this->rootUrl->file("ayre/assets/images/touch-icon-precomposed.png") ?>">
	<script src="<?= $this->rootUrl->file("ayre/assets/build/polyfills{$code}.js") ?>"></script>
	<?= $content->head ?>
</head>
<body class="<?= isset($class) ? $class : '' ?>">
	<?= $content->body ?>
	<script>
		var Ayre = <?= json_encode(\Coast\array_merge_smart(
			isset($opts) ? $opts : [], [
			'baseUrl'		=> (string) $this->url->baseUrl(),
			'rootBaseUrl'	=> (string) $this->rootUrl->baseUrl(),
		])) ?>;
		Ayre.DOMReady = function(a,b,c){b=document,c='addEventListener';b[c]?b[c]('DOMContentLoaded',a):window.attachEvent('onload',a)}
   		Ayre.DOMReady(function() {
   			var scripts = [
   				'<?= $this->rootUrl->file("ayre/assets/build/scripts{$code}.js") ?>',
   				'<?= $this->rootUrl->file("ayre/assets/build/editor{$code}.js") ?>'
   			];
   			for (var i = 0; i < scripts.length; i++) {
   				var el = document.createElement('script');
				el.src = scripts[i];
				document.body.appendChild(el);
   			}
		});
	</script>
	<?= $content->foot ?>
</body>
</html>