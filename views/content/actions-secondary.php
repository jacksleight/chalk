<? if (!$content->isArchived() && !$content->isNewMaster()) { ?>
	<li><a href="<?= $this->url([
		'entity'	=> $entity->name,
		'action'	=> 'status',
		'content'	=> $content->id,
	], 'content', true) . $this->url->query([
		'status' => \Ayre::STATUS_ARCHIVED,
	]) ?>" class="btn btn-negative btn-quiet confirmable">
		<i class="fa fa-archive"></i>
		Archive <?= $entity->singular ?>
	</a></li>
<? } ?>
<? if ($content->isArchived()) { ?>
	<li><a href="<?= $this->url([
		'entity'	=> $entity->name,
		'action'	=> 'delete',
		'content'	=> $content->id,
	], 'content', true) ?>" class="btn btn-negative btn-quiet confirmable" data-message="Are you sure?<?= "\n\n" ?>This will delete the <?= strtolower($entity->singular) ?>  and cannot be undone.">
		<i class="fa fa-trash-o"></i>
		Delete <?= $entity->singular ?>
	</a></li>
<? } ?>