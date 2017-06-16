<?php
$items = [];
?>
<?php foreach ($value as $i => $item) { ?>
    <?php $this->start() ?>
        <div class="form-group form-group-vertical">
            <?= $this->render('element/input_chalk', [
                'name'    => "{$md['contextName']}[{$i}][value]",
                'id'      => "_{$md['contextName']}[{$i}][value]",
                'value'   => $item['value'],
                'scope'   => isset($scope) ? $scope : null,
                'filters' => isset($filters) ? $filters : null,
            ], 'core') ?>
        </div>
    <?php $items[] = $this->end() ?>
<?php } ?>
<? if (isset($stackable) ? $stackable : true) { ?>
    <?php $this->start() ?>
        <div class="form-group form-group-vertical">
            <?= $this->render('element/input_chalk', [
                'name'    => "{$md['contextName']}[{{i}}][value]",
                'id'      => "_{$md['contextName']}[{{i}}][value]",
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