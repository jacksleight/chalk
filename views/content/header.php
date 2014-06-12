<h1>
	<? if (!$content->isNewMaster()) { ?>
		<?= $content->name ?>
	<? } else { ?>
		New <?= $entity->singular ?>
	<? } ?>
</h1>