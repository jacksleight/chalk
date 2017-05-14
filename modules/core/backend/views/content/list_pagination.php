<ul class="toolbar autosubmitable">
    <?= $this->partial('pagination-top') ?>
    <li>
        <?= $this->render('/element/form-input', [
            'type'      => 'paginator',
            'entity'    => $index,
            'name'      => 'page',
            'limit'     => $index->limit,
            'count'     => $contents->count(),
        ], 'core') ?>
    </li>
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
    <?= $this->partial('pagination-bottom') ?>
</ul>