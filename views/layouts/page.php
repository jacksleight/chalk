<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<nav class="sidebar">
	<ul class="nav">
		<li>
			<a href="<?= $this->url([
				'controller'	=> 'content',
				'type'			=> 'core_file',
			], 'entity', true) ?>">
				Files
			</a>
		</li>
		<li>
			<a href="<?= $this->url([
				'controller'	=> 'content',
				'type'			=> 'core_document',
			], 'entity', true) ?>">
				Documents
			</a>
		</li>
	</ul>
</nav>
<section class="main" role="main">
	<?= $content->main ?>
	<footer class="footer c" role="contentinfo">
		<p>Ayre 0.1.0 © <?= date('Y') ?> <a href="http://jacksleight.com/">Jack Sleight</a></p>
	</footer>
</section>