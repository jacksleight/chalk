<?php if (!$content->isArchived() && !$content->isNewMaster()) { ?>
	<li><a href="<?= $this->url([
		'entity'	=> $info->name,
		'action'	=> 'archive',
		'content'	=> $content->id,
	], 'content', true) ?>" class="btn btn-negative btn-quiet confirmable icon-archive">
		Archive <?= $info->singular ?>
	</a></li>
<?php } ?>
<?php if ($content->isArchived()) { ?>
	<li><a href="<?= $this->url([
		'entity'	=> $info->name,
		'action'	=> 'delete',
		'content'	=> $content->id,
	], 'content', true) ?>" class="btn btn-negative btn-quiet confirmable icon-delete" data-message="Are you sure?<?= "\n" ?>This will delete the <?= strtolower($info->singular) ?> and cannot be undone.">
		Delete <?= $info->singular ?>
	</a></li>
<?php } ?>