<ul class="toolbar toolbar-right autosubmitable">
    <li class="toolbar-gap">
        Show&nbsp;
        <?= $this->render('/element/form-input', array(
            'type'   => 'select',
            'entity' => $index,
            'name'   => 'limit',
            'null'   => 'All',
        )) ?>
    </li>
    <li class="toolbar-gap">
        Sort&nbsp;
        <?= $this->render('/element/form-input', array(
            'type'   => 'select',
            'entity' => $index,
            'name'   => 'sort',
            'null'   => 'Default',
        )) ?>
    </li>
    <? if ($isEditAllowed) { ?>
        <li class="toolbar-gap">
            Selected&nbsp;
            <?= $this->render('/element/form-input', array(
                'type'   => 'select',
                'entity' => $index,
                'name'   => 'batch',
                'null'   => 'Action',
                'class'  => 'confirmable',
            )) ?>
        </li>
    <? } ?>
</ul>
<?= $this->render('/element/form-input', [
    'type'      => 'paginator',
    'entity'    => $index,
    'name'      => 'page',
    'limit'     => $index->limit,
    'count'     => $contents->count(),
]) ?>