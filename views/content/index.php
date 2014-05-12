<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

<? if ($entityType->name != 'core_content') { ?>
	<?= $this->render("/{$entityType->entity->path}/index") ?>
<? } ?>