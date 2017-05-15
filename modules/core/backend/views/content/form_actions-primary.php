<ul class="toolbar toolbar-right">
	<?php if (!$entity->isArchived()) { ?>
		<li><button class="btn btn-positive icon-ok">
			Save <?= $info->singular ?>
		</button></li>
	<?php } else { ?>
		<li><a href="<?= $this->url([
			'action'	=> 'restore',
		]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-positive icon-restore">
			Restore <?= $info->singular ?>
		</a></li>
	<?php } ?>
</ul>