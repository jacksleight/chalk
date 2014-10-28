<?php $this->layout('/layout/page_content') ?>
<?php $this->block('main') ?>

<?php if ($info->class != 'Chalk\Core\Content') { ?>
	<?= $this->render("/{$info->local->path}/form", [], $info->module->class) ?>
<?php } ?>