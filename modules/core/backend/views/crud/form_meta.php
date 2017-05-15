<ul class="meta meta-right">
	<?= $this->partial('meta-right-top') ?>
	<?= $this->partial('meta-right-bottom') ?>
</ul>
<ul class="meta">
	<?= $this->partial('meta-top') ?>
	<li class="icon-<?= $info->icon ?>">
		<?= $info->singular ?>
	</li>
	<?= $this->partial('meta-bottom') ?>
</ul>