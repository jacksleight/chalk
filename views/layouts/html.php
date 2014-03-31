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
	<link rel="stylesheet" href="<?= $this->url->file("public/build/styles{$code}.css") ?>">
	<link rel="stylesheet" href="<?= $this->url->file("public/build/demo{$code}.css") ?>">
	<link rel="shortcut icon" href="<?= $this->url->file("public/images/favicon.ico") ?>">
	<link rel="apple-touch-icon-precomposed" href="<?= $this->url->file("public/images/touch-icon-precomposed.png") ?>">
	<script src="<?= $this->url->file("public/build/polyfills{$code}.js") ?>"></script>
	<?= $content->head ?>
</head>
<body>
	<?= $content->body ?>
	<script>
		var App = App || {};
		App.options = <?= json_encode(\Coast\array_merge_smart(
			isset($options) ? $options : [], [
			'base'		=> $this->url->base(),
			'path'		=> $req->path(),
			'privacy'	=> $this->url('privacy-policy'),
		])) ?>;
		App.DOMReady = function(a,b,c){b=document,c='addEventListener';b[c]?b[c]('DOMContentLoaded',a):window.attachEvent('onload',a)}
   		App.DOMReady(function() {
			var el = document.createElement('script');
			el.src = '<?= $this->url->file("public/build/scripts{$code}.js") ?>';
			document.body.appendChild(el);
		});
	</script>
	<?= $content->foot ?>
</body>
</html>