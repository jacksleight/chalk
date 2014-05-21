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
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,600' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?= $this->rootUrl->file("vendor/jacksleight/ayre/assets/build/styles{$code}.css") ?>">
	<link rel="shortcut icon" href="<?= $this->rootUrl->file("vendor/jacksleight/ayre/assets/images/favicon.ico") ?>">
	<link rel="apple-touch-icon-precomposed" href="<?= $this->rootUrl->file("vendor/jacksleight/ayre/assets/images/touch-icon-precomposed.png") ?>">
	<script src="<?= $this->rootUrl->file("vendor/jacksleight/ayre/assets/build/polyfills{$code}.js") ?>"></script>
	<?= $content->head ?>
</head>
<body class="<?= isset($class) ? $class : '' ?>">
	<?= $content->body ?>
	<script>
		var Ayre = <?= json_encode([
			'baseUrl'		=> (string) $this->url->baseUrl(),
			'rootBaseUrl'	=> (string) $this->rootUrl->baseUrl(),
			'prefs'			=> $req->user->prefs(),
		]) ?>;
	</script>
	<script src="<?= $this->rootUrl->file("vendor/jacksleight/ayre/assets/build/scripts{$code}.js") ?>"></script>
   	<script src="<?= $this->rootUrl->file("vendor/jacksleight/ayre/assets/build/editor{$code}.js") ?>" async></script>
	<?= $content->foot ?>
</body>
</html>