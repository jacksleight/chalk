<?php $this->parent('/layout/page_content') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>">
	<?php
	$contents = $this->em($req->info)
		->paged($index->toArray(), null, $index->limit, $index->page);
	?>
	<?= $this->child("/{$info->local->path}/list", [
		'contents' => $contents,
	], $info->module->class) ?>
</form>