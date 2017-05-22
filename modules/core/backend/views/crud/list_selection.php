<ul class="toolbar toolbar-right autosubmitable">
    <?= $this->partial('selection-top') ?>
    <?php if (in_array('update', $actions)) { ?>
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
            <li><button rel="modal" formaction="<?= $this->url([
                    'controller' => 'tag',
                    'action'     => 'manage',
                ], 'core_index', true) ?>" class="btn btn-out btn-lighter icon-price-tag">
                    Manage Tags
            </button></li>
        <?php } ?>
    <?php } else if (in_array('select', $actions)) { ?>
        <li><button formaction="<?= $this->url([
            'action' => 'select',
        ]) ?>" class="btn btn-positive icon-ok">
            Select Items
        </button></li>
    <?php } ?>
    <?= $this->partial('selection-bottom') ?>
</ul>