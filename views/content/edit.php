<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<? if ($entityType->name != 'core_content') { ?>
	<?= $this->render("/{$entityType->local->path}/form") ?>
<? } ?>