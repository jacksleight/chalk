<? $this->layout('/layouts/page_content') ?>
<? $this->block('main') ?>

<? if ($entityType->name != 'core_content') { ?>
	<?= $this->render("/{$entityType->entity->path}/index") ?>
<? } ?>