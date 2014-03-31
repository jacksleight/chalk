<? $this->layout('/layouts/html') ?>
<? $this->block('body') ?>

<div class="outer">
	<header class="header" role="banner"><div class="wrap">
		<a href="<?= $this->url() ?>" class="logo">
			Ayre
		</a>
		<nav class="links" role="navigation">
			<?= $this->render('nav', ['items' => [
				'About'		=> [],
				'Contact'	=> [],
			]]) ?>
		</nav>
	</div></header>
	<nav class="nav" role="navigation"><div class="wrap">
		<?= $this->render('nav', ['items' => [
			'Home'		=> [],
			'News'		=> [],
			'Events'	=> [],
			'Demo'		=> [],
		]]) ?>
	</div></nav>
	<?= $content->main ?>
	<div class="outer-footer"></div>
</div>
<footer class="footer c" role="contentinfo"><div class="wrap">
	<p class="credit">Site by <a href="http://jacksleight.com/">Jack Sleight</a></p>
	<p>© <?= date('Y') ?> Foundation</p>
	<nav class="links" role="navigation">
		<?= $this->render('nav', ['items' => [
			'Accessiblity'			=> [],
			'Terms & Conditions'	=> [],
			'Privacy Policy'		=> [],
		]]) ?>
	</nav>
</div></footer>