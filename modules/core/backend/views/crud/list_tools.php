<ul class="toolbar toolbar-right">
    <?= $this->partial('tools-top') ?>
    <?php if ($isAddAllowed) { ?>
        <li><a href="<?= $this->url([
                'action'  => 'update',
                'content' => null,
            ]) ?>" class="btn btn-focus icon-add">
                New <?= $info->singular ?>
        </a></li>
    <?php } ?>
    <?= $this->partial('tools-bottom') ?>
</ul>