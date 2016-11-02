<ul class="toolbar toolbar-right autosubmitable">
    <li class="toolbar-gap">
        Show&nbsp;
        <?= $this->render('/element/form-input', [
            'type'   => 'select',
            'entity' => $index,
            'name'   => 'limit',
            'null'   => 'All',
        ], 'core') ?>
    </li>
    <li class="toolbar-gap">
        Sort&nbsp;
        <?= $this->render('/element/form-input', [
            'type'   => 'select',
            'entity' => $index,
            'name'   => 'sort',
            'null'   => 'Default',
        ], 'core') ?>
    </li>
    <?php if ($isEditAllowed) { ?>
        <li class="toolbar-gap">
            Selected&nbsp;
            <?= $this->render('/element/form-input', [
                'type'   => 'select',
                'entity' => $index,
                'name'   => 'batch',
                'null'   => 'Action',
                'class'  => 'confirmable',
            ], 'core') ?>
        </li>
    <?php } ?>
</ul>
<?= $this->render('/element/form-input', [
    'type'      => 'paginator',
    'entity'    => $index,
    'name'      => 'page',
    'limit'     => $index->limit,
    'count'     => $contents->count(),
], 'core') ?>