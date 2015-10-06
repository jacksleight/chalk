<?php $this->outer('/layout/page_content', [
    'title' => $info->plural,
]) ?>
<?php $this->block('main') ?>
		
<form action="<?= $this->url->route() ?>" novalidate>
	<?php
	$contents = $this->em($req->info)
		->paged($index->toArray());
	?>
	<?= $this->inner("/{$info->local->path}/list", [
		'contents' => $contents,
	], $info->module->name) ?>
</form>