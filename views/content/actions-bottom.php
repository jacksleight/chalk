<ul class="toolbar">
	<? if (!$entity->isArchived()) { ?>
		<li><button class="btn-focus">
			<i class="fa fa-check"></i>
			Save <?= $entityType->singular ?>
		</button></li>
	<? } else { ?>
		<li><a href="<?= $this->url([
			'action' => 'restore'
		]) ?>" class="btn btn-focus">
			<i class="fa fa-repeat"></i>
			Restore <?= $entityType->singular ?>
		</a></li>
	<? } ?>
</ul>
<ul class="toolbar">
	<? if (!$entity->isArchived() && !$entity->isNewMaster()) { ?>
		<li><a href="<?= $this->url([
			'action' => 'status']) . $this->url->query([
			'status' => \Ayre::STATUS_ARCHIVED,
		]) ?>" class="btn btn-negative btn-quiet">
			<i class="fa fa-archive"></i>
			Archive <?= $entityType->singular ?>
		</a></li>
	<? } ?>
</ul>