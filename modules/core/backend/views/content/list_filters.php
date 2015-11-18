<?php
$this->params([
    'filterFields' => $filterFields = isset($filterFields) ? $filterFields : [
        [
            'class'   => 'flex-2',
            'style'   => 'max-width: 200px;',
            'partial' => 'date-min',
            'params'  => ['property' => 'modify', 'placeholder' => 'Updated'],
        ],
    ],
]);
foreach ($filterFields as $i => $col) {
    $filterFields[$i] = $col + [
        'class'    => null,
        'style'    => null,
        'partial'  => null,
        'params'   => [],
    ];
}
?>

<ul class="toolbar toolbar-flush autosubmitable">
    <li class="flex-3">
        <?= $this->render('/element/form-input', array(
            'type'          => 'input_search',
            'entity'        => $index,
            'name'          => 'search',
            'autofocus'     => true,
            'placeholder'   => 'Search…',
        ), 'core') ?>
    </li>
    <?php
    $subtypes = $this->em($info)->subtypes(['types' => isset($filters) ? $filters : null]);
    $values   = [];
    $class    = $info->class;
    foreach ($subtypes as $subtype) {
        $values[$subtype['subtype']] = $class::staticSubtypeLabel($subtype['subtype']);
    }
    asort($values);
    ?>
    <? if (count($subtypes)) { ?>
        <li class="flex-2">
            <?= $this->render('/element/form-input', array(
                'type'          => 'dropdown_multiple',
                'entity'        => $index,
                'name'          => 'subtypes',
                'icon'          => 'icon-subtype',
                'placeholder'   => 'Type',
                'values'        => $values,
            ), 'core') ?>
        </li>
    <? } ?>
    <?php foreach ($filterFields as $field) { ?>
        <li class="<?= $field['class'] ?>" style="<?= $col['style'] ?>">
            <?= $this->inner("list_filters-{$field['partial']}", ['index' => $index] + $field['params']) ?>
        </li>
    <?php } ?>
    <li style="max-width: 200px;">
        <?= $this->render('/element/form-input', array(
            'type'          => 'dropdown_multiple',
            'entity'        => $index,
            'name'          => 'statuses',
            'icon'          => 'icon-status',
            'placeholder'   => 'Status',
        ), 'core') ?>
    </li>
</ul>