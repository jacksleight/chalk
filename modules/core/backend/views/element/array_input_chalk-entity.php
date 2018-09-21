<?php
$items = [];
?>
<?= $this->inner('input_hidden', [
    'name'  => "{$md['contextName']}[]",
    'value' => '',
]) ?>
<?php foreach ($value as $i => $item) { ?>
    <?php $this->start() ?>
        <div class="form-group form-group-vertical">
            <?= $this->render('element/input_chalk-entity', [
                'name'    => "{$md['contextName']}[{$i}]",
                'id'      => "_{$md['contextName']}[{$i}]",
                'value'   => $item,
                'scope'   => isset($scope) ? $scope : null,
                'filters' => isset($filters) ? $filters : null,
            ], 'core') ?>
        </div>
    <?php $items[] = $this->end() ?>
<?php } ?>
<? if (isset($stackable) ? $stackable : true) { ?>
    <?php $this->start() ?>
        <div class="form-group form-group-vertical">
            <?= $this->render('element/input_chalk-entity', [
                'name'    => "{$md['contextName']}[{{i}}]",
                'id'      => "_{$md['contextName']}[{{i}}]",
                'value'   => null,
                'scope'   => isset($scope) ? $scope : null,
                'filters' => isset($filters) ? $filters : null,
            ], 'core') ?>
        </div>
    <?php $template = $this->end() ?>
    <?= $this->render('element/stackable', [
        'items'    => $items,
        'template' => $template,
        'fixed'    => true,
    ], 'core') ?>
<? } else { ?>
    <?= implode(null, $items) ?>
<? } ?>