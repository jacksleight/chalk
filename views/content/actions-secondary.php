<? if (!$content->isArchived() && !$content->isNewMaster()) { ?>
	<li><a href="<?= $this->url([
		'entityType'	=> $entityType->slug,
		'action'		=> 'status',
		'content'		=> $content->id,
	], 'content', true) . $this->url->query([
		'status' => \Ayre::STATUS_ARCHIVED,
	]) ?>" class="btn btn-negative btn-quiet">
		<i class="fa fa-archive"></i>
		Archive <?= $entityType->singular ?>
	</a></li>
<? } ?>