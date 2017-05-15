<?php
$this->params([
    'tableCols' => $tableCols = isset($tableCols) ? $tableCols : [
        [
            'label'   => 'Name',
            'partial' => 'name',
        ],
    ],
]);
foreach ($tableCols as $i => $col) {
    $tableCols[$i] = $col + [
        'label'    => null,
        'class'    => null,
        'style'    => null,
        'partial'  => null,
        'params'   => [],
    ];
}
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
                        <?= $this->render('/behaviour/selectable/checkbox', [
                            'entity'   => $entity,
                            'entities' => $index->entities,
                        ], 'core') ?>
                    </td>
                    <?php foreach ($tableCols as $col) { ?>
                        <td class="<?= $col['class'] ?>" style="<?= $col['style'] ?>">
                            <?php if (isset($col['partial'])) { ?>
                                <?= $this->inner("list_table-{$col['partial']}", ['entity' => $entity] + $col['params']) ?>
                            <?php } else if (isset($col['func'])) { ?>
                                <?= $col['func']($entity, $col['params']) ?>
                            <?php } ?>
                        </td>
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