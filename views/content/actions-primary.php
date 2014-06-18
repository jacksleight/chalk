<? if (!$content->isArchived()) { ?>
	<li><button class="btn btn-focus">
		<i class="fa fa-check"></i>
		Save <?= $entity->singular ?>
	</button></li>
<? } else { ?>
	<li><a href="<?= $this->url([
		'entity'	=> $entity->name,
		'action'		=> 'restore',
		'content'		=> $content->id,
	], 'content', true) ?>" class="btn btn-focus">
		<i class="fa fa-repeat"></i>
		Restore <?= $entity->singular ?>
	</a></li>
<? } ?>