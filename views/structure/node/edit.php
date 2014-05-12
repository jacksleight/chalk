<? $this->layout('/layouts/page_structure') ?>
<? $this->block('main') ?>

<? if ($entityType->name != 'core_content') { ?>
	<?= $this->render("/{$entityType->entity->path}/form") ?>
<? } ?>