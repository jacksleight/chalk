<?php if (!$content->isArchived()) { ?>
	<li><button class="btn btn-positive icon-ok">
		Save <?= $info->singular ?>
	</button></li>
<?php } else { ?>
	<li><a href="<?= $this->url([
		'entity'	=> $info->name,
		'action'		=> 'restore',
		'content'		=> $content->id,
	], 'content', true) ?>" class="btn btn-positive icon-restore">
		Restore <?= $info->singular ?>
	</a></li>
<?php } ?>