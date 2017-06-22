<?php $this->outer('layout/page', [
    'title' => $info->plural,
], 'core') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" novalidate>
	<?php
	$entities = $this->em($info)
        ->all($model->toArray(), [], Chalk\Repository::FETCH_ALL_PAGED);
	?>
	<?= $this->inner("list", [
        'entities' => $entities,
        'group'    => 'structure',
        'skip'     => 'content',
		'indent'   => 'depthFlat',
	]) ?>
    <?= $this->url->queryInputs([
        'mode'         => $model->mode,
        'filtersList'  => $model->filtersList,
        'selectedType' => $model->selectedType,
        'tagsList'     => $model->tagsList,
    ], true) ?>
</form>