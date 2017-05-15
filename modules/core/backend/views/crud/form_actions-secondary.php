<ul class="toolbar">
	<?php if ($isAddAllowed && !$entity->isNew()) { ?>
		<li><a href="<?= $this->url([
			'action'	=> 'delete',
		]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-negative btn-out confirmable icon-delete" data-message="Are you sure?<?= "\n" ?>This will delete the <?= strtolower($info->singular) ?> and cannot be undone.">
			Delete <?= $info->singular ?>
		</a></li>
	<?php } ?>
</ul>