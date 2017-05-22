<ul class="toolbar toolbar-right">
    <?= $this->partial('tools-top') ?>
    <?php if (in_array('create', $actions)) { ?>
        <ul class="toolbar">
            <li><span class="btn btn-focus icon-upload uploadable-button">
                Upload <?= $info->plural ?>
            </span></li>
        </ul>
    <?php } ?>
    <?= $this->partial('tools-bottom') ?>
</ul>