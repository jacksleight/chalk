<ul class="toolbar autosubmitable">
    <?= $this->partial('pagination-top') ?>
    <li>
        <?= $this->render('/element/form-input', [
            'type'      => 'paginator',
            'entity'    => $model,
            'name'      => 'page',
            'limit'     => $model->limit,
            'count'     => $entities->count(),
        ], 'core') ?>
    </li>
    <li class="toolbar-gap">
        Show&nbsp;
        <?= $this->render('/element/form-input', [
            'type'   => 'select',
            'entity' => $model,
            'name'   => 'limit',
            'null'   => 'All',
        ], 'core') ?>
    </li>
    <li class="toolbar-gap">
        Sort&nbsp;
        <?= $this->render('/element/form-input', [
            'type'   => 'select',
            'entity' => $model,
            'name'   => 'sort',
            'null'   => 'Default',
        ], 'core') ?>
    </li>
    <?= $this->partial('pagination-bottom') ?>
</ul>