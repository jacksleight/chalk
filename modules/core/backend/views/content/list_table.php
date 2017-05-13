<?php
$this->params([
    'tableCols' => $tableCols = isset($tableCols) ? $tableCols : [
        [
            'label'   => 'Updated',
            'class'   => 'col-right col-contract',
            'partial' => 'date-user',
            'params'  => ['property' => 'modify'],
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
        <col>
        <?php foreach ($tableCols as $col) { ?>
            <col class="<?= $col['class'] ?>">        
        <?php } ?>
        <col class="col-right col-badge">
    </colgroup>
    <thead>
        <tr>
            <th scope="col" class="col-select">
                <input type="checkbox" id="select" class="multiselectable-all"><label for="select"></label>
                <?= $this->render('/element/form-input', [
                    'type'   => 'input_hidden',
                    'entity' => $index,
                    'name'   => 'contentIds',
                    'class'  => 'multiselectable-values',
                ], 'core') ?>
            </th>
            <th scope="col">Name</th>
            <?php foreach ($tableCols as $col) { ?>
                <th scope="col" class="<?= $col['class'] ?>" style="<?= $col['style'] ?>">
                    <?= $col['label'] ?>
                </th>
            <?php } ?>
            <th scope="col" class="col-right col-badge">Status</th>
        </tr>
    </thead>
    <tbody class="<?= $isUploadable ? 'uploadable-list' : null ?>">
        <?php if (count($contents)) { ?>
            <?php foreach ($contents as $content) { ?>
                <tr class="selectable <?= $isEditAllowed ? 'clickable' : null ?>">
                    <td class="col-select">
                        <?= $this->render('/behaviour/selectable/checkbox', [
                            'entity'   => $content,
                            'entities' => $index->contents,
                        ], 'core') ?>
                    </td>
                    <th scope="row">
                        <?php if ($isEditAllowed) { ?>
                            <a href="<?= $this->url([
                                'action' => 'edit',
                                'id'     => $content->id,
                            ]) ?>"><?php } ?><?= $content->name ?><?php if ($isEditAllowed) { ?></a>
                        <?php } ?>
                        <br>
                        <small><?= implode(' – ', $content->previewText($info->class != 'Chalk\Core\Content')) ?></small>
                    </th>
                    <?php foreach ($tableCols as $col) { ?>
                        <td class="<?= $col['class'] ?>" style="<?= $col['style'] ?>">
                            <?= $this->inner("list_table-{$col['partial']}", ['content' => $content] + $col['params']) ?>
                        </td>
                    <?php } ?>
                    <td class="col-right col-badge">
                        <span class="badge badge-upper badge-<?= $this->app->statusClass($content->status) ?>">
                            <?= $content->status ?>
                        </span>
                    </td>
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