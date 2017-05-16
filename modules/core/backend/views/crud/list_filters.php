<?php
$this->params([
    'filterFields' => $filterFields = [
        'search' => is_a($info->class, 'Chalk\Core\Behaviour\Searchable', true) ? [
            'class'   => 'flex-3',
            'partial' => 'search',
        ] : null,
        'modifyDateMin' => is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true) ? [
            'class'   => 'flex-2',
            'partial' => 'date-min',
            'params'  => ['property' => 'modify', 'placeholder' => 'Updated'],
        ] : null,
        'status' => is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true) ? [
            'class'   => 'flex-2',
            'partial' => 'status',
        ] : null,
    ] + (isset($filterFields) ? $filterFields : []),
]);
$i = 0;
foreach ($filterFields as $key => $field) {
    if (!isset($field)) {
        unset($filterFields[$key]);
        continue;
    }
    $filterFields[$key] = $field + [
        'i'        => $i,
        'class'    => null,
        'style'    => null,
        'partial'  => null,
        'func'     => null,
        'params'   => [],
        'sort'     => ($i + 1) * 10,
    ];
    $i++;
}
uasort($filterFields, function($a, $b) {
    return $a['sort'] == $b['sort']
        ? $a['i'] - $b['i']
        : $a['sort'] - $b['sort'];
});
?>

<ul class="toolbar toolbar-flush autosubmitable">
    <?php foreach ($filterFields as $field) { ?>
        <?php
        if (isset($field['partial'])) {
            $html = $this->inner("list_filters-{$field['partial']}", ['index' => $index] + $field['params']);
        } else if (isset($field['func'])) {
            $html = $field['func']($index, $field['params']);
        }
        ?>
        <?php if (strlen($html)) { ?>
            <li class="<?= $field['class'] ?>" style="<?= $field['style'] ?>">
                <?= $html ?>
            </li>
        <?php } ?>
    <?php } ?>
</ul>