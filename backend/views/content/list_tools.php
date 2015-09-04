<ul class="toolbar toolbar-right">
    <?= $this->content('tools-top') ?>
    <? if ($isUploadable) { ?>
        <ul class="toolbar">
            <li><span class="btn btn-focus icon-upload uploadable-button">
                Upload <?= $info->plural ?>
            </span></li>
        </ul>
    <? } else if ($isNewAllowed) { ?>
        <li><a href="<?= $this->url([
                'action' => 'edit',
            ]) ?>" class="btn btn-focus icon-add">
                New <?= $info->singular ?>
        </a></li>
    <? } ?>
    <?= $this->content('tools-bottom') ?>
</ul>