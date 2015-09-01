<?php $this->outer('/layout/html') ?>
<?php $this->block('body') ?>

<ul class="notifications"></ul>
<div class="center"><div>
	<?= $this->content('main') ?>
	<footer class="copyright" role="contentinfo">
		<p><a href="<?= $this->url([], 'about', true) ?>" rel="modal">Chalk <?= \Chalk\Chalk::VERSION ?></a></p>
	</footer>
</div></div>