<?php $this->layout('/layouts/page_content') ?>
<?php $this->block('main') ?>

<?php if ($entity->name != 'Chalk\Core\Content') { ?>
	<?= $this->render("/{$entity->local->path}/form", [], $entity->module->class) ?>
<?php } ?>