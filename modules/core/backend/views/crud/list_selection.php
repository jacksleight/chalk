<ul class="toolbar toolbar-right autosubmitable">
    <?= $this->partial('selection-top') ?>
    <?php if ($isEditAllowed) { ?>
        <li class="toolbar-gap">
            <?= $this->render('/element/form-input', [
                'type'   => 'select',
                'entity' => $index,
                'name'   => 'batch',
                'null'   => 'Action',
                'class'  => 'confirmable',
            ], 'core') ?>
        </li>
    <?php } ?>
    <?= $this->partial('selection-bottom') ?>
</ul>