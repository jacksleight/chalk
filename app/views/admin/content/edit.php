<?php $this->layout('/layouts/page_content') ?>
<?php $this->block('main') ?>

<?php if ($entity->name != 'core_content') { ?>
	<?= $this->render("/{$entity->local->path}/form", [], $entity->module->name) ?>
<?php } ?>