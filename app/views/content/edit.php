<?php $this->parent('/layout/page_content') ?>
<?php $this->block('main') ?>

<?php if ($info->class != 'Chalk\Core\Content') { ?>
	<?= $this->child("/{$info->local->path}/form", [], $info->module->class) ?>
<?php } ?>