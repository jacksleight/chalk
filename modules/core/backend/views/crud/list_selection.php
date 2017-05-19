<ul class="toolbar toolbar-right autosubmitable">
    <?= $this->partial('selection-top') ?>
    <?php if ($isEditAllowed) { ?>
        <li class="toolbar-gap">
            Selected&nbsp;
            <?= $this->render('/element/form-input', [
                'type'   => 'select',
                'entity' => $model,
                'name'   => 'batch',
                'null'   => 'Action',
                'class'  => 'confirmable autosubmitable-post',
            ], 'core') ?>
        </li>
    <?php } ?>
    <?= $this->partial('selection-bottom') ?>
</ul>