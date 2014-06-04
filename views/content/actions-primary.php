<? if (!$content->isArchived()) { ?>
	<li><button class="btn btn-focus">
		<i class="fa fa-check"></i>
		Save <?= $entityType->singular ?>
	</button></li>
<? } else { ?>
	<li><a href="<?= $this->url([
		'entityType'	=> $entityType->slug,
		'action'		=> 'restore',
		'id'			=> $content->id,
	], 'content', true) ?>" class="btn btn-focus">
		<i class="fa fa-repeat"></i>
		Restore <?= $entityType->singular ?>
	</a></li>
<? } ?>