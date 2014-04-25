<h1>
	<? if (!$entity->isNewMaster()) { ?>
		<?= $entity->name ?>
	<? } else { ?>
		New <?= $entityType->singular ?>
	<? } ?>
</h1>