<?php $this->parent('/layout/page_content') ?>
<?php $this->block('main') ?>

<?= $this->child("/{$info->local->path}/form", [], $info->module->class) ?>