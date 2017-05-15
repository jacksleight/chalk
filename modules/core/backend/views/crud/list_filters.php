<?php
$this->params([
    'filterFields' => $filterFields = isset($filterFields) ? $filterFields : [],
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
    <?php if (is_a($info->class, 'Chalk\Core\Behaviour\Searchable', true)) { ?>
        <li class="flex-3">
            <?= $this->render('/element/form-input', array(
                'type'          => 'input_search',
                'entity'        => $index,
                'name'          => 'search',
                'autofocus'     => true,
                'placeholder'   => 'Search…',
            ), 'core') ?>
        </li>
    <?php } ?>
    <?php foreach ($filterFields as $field) { ?>
        <li class="<?= $field['class'] ?>" style="<?= $field['style'] ?>">
            <?= $this->inner("list_filters-{$field['partial']}", ['index' => $index] + $field['params']) ?>
        </li>
    <?php } ?>
</ul>