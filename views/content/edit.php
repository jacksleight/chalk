<? $this->layout('/layouts/page_content') ?>
<? $this->block('main') ?>

<? if ($entity->name != 'core_content') { ?>
	<?= $this->render("/{$entity->entity->path}/form", [], $entity->module->name) ?>
<? } ?>