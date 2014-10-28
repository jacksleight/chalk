<?php if (!$content->isArchived()) { ?>
	<li><button class="btn btn-focus">
		<i class="fa fa-check"></i>
		Save <?= $info->singular ?>
	</button></li>
<?php } else { ?>
	<li><a href="<?= $this->url([
		'entity'	=> $info->name,
		'action'		=> 'restore',
		'content'		=> $content->id,
	], 'content', true) ?>" class="btn btn-focus">
		<i class="fa fa-repeat"></i>
		Restore <?= $info->singular ?>
	</a></li>
<?php } ?>