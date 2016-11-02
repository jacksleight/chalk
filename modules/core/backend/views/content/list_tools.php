<ul class="toolbar toolbar-right">
    <?= $this->partial('tools-top') ?>
    <?php if ($isUploadable) { ?>
        <ul class="toolbar">
            <li><span class="btn btn-focus icon-upload uploadable-button">
                Upload <?= $info->plural ?>
            </span></li>
        </ul>
    <?php } else if ($isAddAllowed) { ?>
        <li><a href="<?= $this->url([
                'entity' => $info->name,
                'action' => 'edit',
            ], 'core_content', true) ?>" class="btn btn-focus icon-add">
                New <?= $info->singular ?>
        </a></li>
    <?php } ?>
    <?= $this->partial('tools-bottom') ?>
</ul>