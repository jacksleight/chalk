<ul class="toolbar toolbar-right autosubmitable">
    <li>
        Show&nbsp;
        <?= $this->render('/element/form-input', array(
            'type'   => 'select',
            'entity' => $index,
            'name'   => 'limit',
            'null'   => 'All',
        )) ?>
    </li>
    <? if ($isEditAllowed) { ?>
        <li>
            &nbsp;
            Selected&nbsp;
            <?= $this->render('/element/form-input', array(
                'type'   => 'select',
                'entity' => $index,
                'name'   => 'action',
                'null'   => 'Action',
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