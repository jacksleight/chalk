<?php $this->layout('/layout/html') ?>
<?php $this->block('body') ?>

<ul class="notifications"></ul>
<div class="center"><div>
	<?= $content->main ?>
	<footer class="footer c" role="contentinfo">
		<p>Chalk <?= \Chalk\Chalk::VERSION ?></p>
	</footer>
</div></div>