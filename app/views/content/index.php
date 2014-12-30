<?php $this->parent('/layout/page_content') ?>
<?php $this->block('main') ?>

<?php
$contents = $this->em($req->info)
	->paged($index->toArray(), null, $index->limit, $index->page);
?>
<?= $this->child("/{$info->local->path}/index", [
	'contents' => $contents,
], $info->module->class) ?>