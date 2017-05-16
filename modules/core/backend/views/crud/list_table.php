<?php
$this->params([
    'tableCols' => $tableCols = [
        'modifyDate' => is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true) ? [
            'label'   => 'Updated',
            'class'   => 'col-right col-contract',
            'partial' => 'date-user',
            'params'  => ['property' => 'modify'],
        ] : null,
        'status' => is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true) ? [
            'label'   => 'Status',
            'class'   => 'col-right col-contract',
            'partial' => 'status',
        ] : null,
    ] + (isset($tableCols) ? $tableCols : []),
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
        'i'        => $i,
        'label'    => null,
        'class'    => null,
        'style'    => null,
        'partial'  => null,
        'func'     => null,
        'params'   => [],
        'sort'     => ($i + 1) * 10,
    ];
    $i++;
}
uasort($tableCols, function($a, $b) {
    return $a['sort'] == $b['sort']
        ? $a['i'] - $b['i']
        : $a['sort'] - $b['sort'];
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
                    'entity' => $index,
                    'name'   => 'entityIds',
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
    <tbody class="<?= $isUploadable ? 'uploadable-list' : null ?>">
        <?php if (count($entities)) { ?>
            <?php foreach ($entities as $entity) { ?>
                <tr class="selectable <?= $isEditAllowed ? 'clickable' : null ?>">
                    <td class="col-select">
                        <?= $this->partial('checkbox', [
                            'entity'   => $entity,
                            'entities' => $index->entities,
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
                    <h2>No <?= $info->plural ?> Found</h2>
                    <?php if (isset($index->search) && strrpos($index->search, '*') === false) { ?>
                        <p>To search for partial words use an asterisk, eg. "<a href="<?= $this->url->query(['search' => "*{$index->search}*"]) ?>"><?= "*{$index->search}*" ?></a>".</p>
                    <?php } else if ($isAddAllowed) { ?>
                        <p>To create a new <?= strtolower($info->singular) ?> click the 'New <?= $info->singular ?>' button above.</p>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>