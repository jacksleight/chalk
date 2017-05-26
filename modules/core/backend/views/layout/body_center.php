<?php $this->outer('/layout/html') ?>
<?php $this->block('body') ?>

<ul class="notifications"></ul>
<div class="center"><div>
	<?= $this->content('main') ?>
	<footer class="copyright" role="contentinfo">
		<p>Chalk <?= Chalk\Chalk::VERSION ?></p>
	</footer>
</div></div>