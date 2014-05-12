<h1>
	<? if (!$content->isNewMaster()) { ?>
		<?= $content->name ?>
	<? } else { ?>
		New <?= $entityType->singular ?>
	<? } ?>
</h1>