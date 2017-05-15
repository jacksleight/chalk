<?php
$path = new Coast\Path($req->path());
$this->outer("layout/page_{$path->part(1)}", [
    'title' => $info->plural,
], $path->part(0));
?>
<?php $this->block('main') ?>
		
<form action="<?= $this->url->route() ?>" novalidate>
	<?php
	$entities = $this->em($req->info)
		->all($index->toArray(), [], Chalk\Repository::FETCH_ALL_PAGED);
	?>
	<?= $this->inner("list", [
		'entities' => $entities,
	]) ?>
    <?= $this->render('/element/form-input', array(
        'type'   => 'input_hidden',
        'entity' => $index,
        'name'   => 'remember',
    ), 'core') ?>
</form>