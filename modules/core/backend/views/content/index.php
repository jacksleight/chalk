<?php $this->outer('layout/page_site', [
    'title' => $info->plural,
], 'core') ?>
<?php $this->block('main') ?>
		
<form action="<?= $this->url->route() ?>" novalidate>
	<?php
	$contents = $this->em($req->info)
		->all($index->toArray(), [], Chalk\Repository::FETCH_ALL_PAGED);
	?>
	<?= $this->inner("list", [
		'contents' => $contents,
	]) ?>
    <?= $this->render('/element/form-input', array(
        'type'          => 'input_hidden',
        'entity'        => $index,
        'name'          => 'remember',
    ), 'core') ?>
</form>