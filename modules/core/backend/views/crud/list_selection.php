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
        <?php if ($info->is->tagable) { ?>
            <li><button formaction="<?= $this->url([
                    'action'  => 'manage-tags',
                ], 'core_index', true) ?>" class="btn btn-out btn-lighter icon-price-tag">
                    Manage Tags
            </button></li>
        <?php } ?>
    <?php } ?>
    <?= $this->partial('selection-bottom') ?>
</ul>