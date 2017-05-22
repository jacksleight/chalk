<?php
$this->params([
    'tableCols' => $tableCols = (isset($tableCols) ? $tableCols : []) + [
        'name' => [
            'label'   => 'Name',
            'partial' => 'preview',
            'sort'    => 10,
        ],
        'date' => $info->is->publishable ? [
            'label'   => 'Updated',
            'class'   => 'col-right col-contract',
            'partial' => 'date-user',
            'params'  => ['property' => 'modify'],
            'sort'    => 80,
        ] : null,
        'status' => $info->is->publishable ? [
            'label'   => 'Status',
            'class'   => 'col-right col-contract col-badge',
            'partial' => 'status',
            'sort'    => 90,
        ] : null,
    ],
]);
$this->params([
    'tableCols' => $tableCols = isset($tableCols) ? $tableCols : [
        [
            'label'   => 'Name',
            'partial' => 'name',
        ],
    ],
]);
$i = 0;
foreach ($tableCols as $key => $col) {
    if (!isset($col)) {
        unset($tableCols[$key]);
        continue;
    }
    $tableCols[$key] = $col + [
        'label'    => null,
        'class'    => null,
        'style'    => null,
        'partial'  => null,
        'func'     => null,
        'params'   => [],
        'sort'     => null,
    ];
    $i++;
}
uasort($tableCols, function($a, $b) {
    return $a['sort'] - $b['sort'];
});
?>

<table class="multiselectable">
    <colgroup>
        <col class="col-select">
        <?php foreach ($tableCols as $col) { ?>
            <col class="<?= $col['class'] ?>">        
        <?php } ?>
    </colgroup>
    <thead>
        <tr>
            <th scope="col" class="col-select">
                <input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
                <?= $this->render('/element/form-input', [
                    'type'   => 'input_hidden',
                    'entity' => $model,
                    'name'   => 'selectedList',
                    'class'  => 'multiselectable-values',
                ], 'core') ?>
            </th>
            <?php foreach ($tableCols as $col) { ?>
                <th scope="col" class="<?= $col['class'] ?>" style="<?= $col['style'] ?>">
                    <?= $col['label'] ?>
                </th>
            <?php } ?>
        </tr>
    </thead>
    <tbody class="uploadable-list">
        <?php if (count($entities)) { ?>
            <?php foreach ($entities as $entity) { ?>
                <tr class="selectable <?= in_array('update', $actions) ? 'clickable' : null ?>">
                    <td class="col-select">
                        <?= $this->partial('checkbox', [
                            'entity'   => $entity,
                            'selected' => $model->selected,
                        ]) ?>
                    </td>
                    <?php foreach ($tableCols as $col) { ?>
                        <?php
                        if (isset($col['partial'])) {
                            $html = $this->inner("list_table-{$col['partial']}", ['entity' => $entity] + $col['params']);
                        } else if (isset($col['func'])) {
                            $html = $col['func']($entity, $col['params']);
                        }
                        ?>
                        <?php if (strlen($html)) { ?>
                            <td class="<?= $col['class'] ?>" style="<?= $col['style'] ?>">
                                <?= $html ?>
                            </td>
                        <?php } ?>
                    <?php } ?>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td class="notice" colspan="<?= 3 + count($tableCols) ?>">
                    <?= $this->partial('notice') ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>