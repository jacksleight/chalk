<?php $this->outer('layout/page', [
    'title' => $info->plural,
], 'core') ?>
<?php $this->block('main') ?>
		
<form action="<?= $this->url->route() ?>" novalidate>
	<?php
	$entities = $this->em($req->info)
		->all($model->toArray(), [], Chalk\Repository::FETCH_ALL_PAGED);
	?>
	<?= $this->inner("list", [
		'entities' => $entities,
	]) ?>
    <? /* $this->render('/element/form-input', array(
        'type'   => 'input_hidden',
        'entity' => $model,
        'name'   => 'remembersList',
    ), 'core') */ ?>
    <?= $this->render('/element/form-input', array(
        'type'   => 'input_hidden',
        'entity' => $model,
        'name'   => 'tagsList',
    ), 'core') ?>
</form>