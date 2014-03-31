<?php
$mode	= $this->app->isDevelopment() ? 'dev' : 'min';
$meta	= $req->getParams('meta');
$title	= (isset($meta['title']) 
	? $meta['title'] . ' – '
	: null) . $this->locale->message('title');
unset($meta['title']);
$lang = $this->locale->getLanguage() . '-' . $this->locale->getRegion();
?>
<!DOCTYPE html>
<html class="no-js" lang="<?= $lang ?>">
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>
	<? foreach ($meta as $name => $value) { ?>
		<meta name="<?= $name ?>" content="<?= $value ?>">
	<? } ?>	
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<link rel="stylesheet" href="<?= $this->url("public/styles/main.{$mode}.css") ?>">
</head>
<body>
	<?= $content[0] ?>
	<script>
		var App = App || {};
		App.options = <?= json_encode(\Coast\array_merge_smart(
			$req->getParams('options'),
			array(
				'basePath'			=> $req->getBase(),
				'localPath'			=> $req->getPath(),
				'privacyPolicyUrl'	=> $this->url('privacy-policy'),
				'analytics'			=> $this->config->analytics,
			)
		)) ?>;
		App.DOMReady = function(a,b,c){b=document,c='addEventListener';b[c]?b[c]('DOMContentLoaded',a):window.attachEvent('onload',a)}
   		App.DOMReady(function() {
			var el = document.createElement('script');
			el.src = '<?= $this->url("public/scripts/main.{$mode}.js") ?>';
			document.body.appendChild(el);
		});
	</script>
	<? if ($this->app->isDevelopment()) { ?>
		<script src="http://localhost:35729/livereload.js"></script>
	<? } ?>
</html>