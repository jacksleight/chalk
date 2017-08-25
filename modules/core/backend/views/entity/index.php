<?php $this->outer('layout/page', [
    'title' => $info->plural,
], 'core') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url([]) ?>" novalidate>
    <?php
    $entities = $this->em($info)
        ->all($model->toArray(), [], Chalk\Repository::FETCH_ALL_PAGED);
    ?>
    <?= $this->inner("list", [
        'entities' => $entities,
    ]) ?>
    <?= $this->url->queryInputs([
        'mode'         => $model->mode,
        'filtersList'  => $model->filtersList,
        'selectedType' => $info->name,
        'tagsList'     => $model->tagsList,
    ], true) ?>
</form>