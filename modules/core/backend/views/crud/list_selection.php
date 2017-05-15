<ul class="toolbar toolbar-right autosubmitable">
    <?php if ($isEditAllowed) { ?>
        <?= $this->partial('selection-top') ?>
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
        <?= $this->partial('selection-bottom') ?>
    <?php } ?>
</ul>