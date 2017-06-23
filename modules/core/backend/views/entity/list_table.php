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
            'params'  => ['name' => 'update'],
            'sort'    => 80,
        ] : null,
        'status' => $info->is->publishable ? [
            'label'   => 'Status',
            'class'   => 'col-right col-contract col-badge',
            'partial' => 'status',
            'params'  => ['name' => 'status'],
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

<?php if (count($entities)) { ?>
    <table class="multiselectable">
        <colgroup>
            <?php if (array_intersect(['batch', 'select-all'], $actions)) { ?>
                <col class="col-select">
            <?php } ?>
            <?php foreach ($tableCols as $col) { ?>
                <col class="<?= $col['class'] ?>">
            <?php } ?>
        </colgroup>
        <thead>
            <tr>
                <?php if (array_intersect(['batch', 'select-all'], $actions)) { ?>
                    <th scope="col" class="col-select">
                        <input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
                        <?= $this->render('/element/form-input', [
                            'type'   => 'input_hidden',
                            'entity' => $model,
                            'name'   => 'selectedList',
                            'class'  => 'multiselectable-values',
                        ], 'core') ?>
                    </th>
                <?php } ?>
                <?php foreach ($tableCols as $col) { ?>
                    <th scope="col" class="<?= $col['class'] ?>" style="<?= $col['style'] ?>">
                        <?= $col['label'] ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentGroup = null;
            ?>
            <?php foreach ($entities as $entity) { ?>
                <?php if (isset($group) && $currentGroup !== $entity->{$group}) { ?>
                    </tbody><thead class="tgroup">
                    <tr>
                        <?php if (array_intersect(['batch', 'select-all'], $actions)) { ?>
                            <th></th>
                        <?php } ?>
                        <th colspan="<?= count($tableCols) ?>">
                            <?= $entity->{$group}->previewName ?>
                        </th>
                    </tr>
                    </thead><tbody>
                    <?php $currentGroup = $entity->{$group} ?>
                <?php } ?>
                <?php
                if (isset($skip) && !isset($entity->{$skip})) {
                    continue;
                }
                ?>
                <tr class="<?= array_intersect(['batch', 'select-all'], $actions) ? 'selectable' : null ?> <?= array_intersect(['update', 'select-one'], $actions) ? 'clickable' : null ?>">
                    <?php if (array_intersect(['batch', 'select-all'], $actions)) { ?>
                        <td class="col-select">
                            <?= $this->partial('checkbox', [
                                'entity' => $entity,
                            ]) ?>
                        </td>
                    <?php } ?>
                    <?php $c = 0 ?>
                    <?php foreach ($tableCols as $col) { ?>
                        <?php
                        if (isset($col['partial'])) {
                            $html = $this->partial("table-{$col['partial']}", ['entity' => $entity] + $col['params']);
                        } else if (isset($col['func'])) {
                            $html = $col['func']($entity, $col['params']);
                        }
                        ?>
                        <?php if (strlen($html)) { ?>
                            <?php
                            $style = $col['style'];
                            if ($c == 0 && isset($indent)) {
                                $padding = $entity->{$indent} * 20;
                                $style .= "; padding-left: {$padding}px";
                            }
                            ?>
                            <td class="<?= $col['class'] ?>" style="<?= $style ?>">
                                <?= $html ?>
                            </td>
                        <?php } ?>
                        <?php $c++ ?>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <div class="notice">
        <?= $this->partial('notice') ?>
    </div>
<?php } ?>