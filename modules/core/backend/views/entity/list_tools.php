<ul class="toolbar toolbar-right">
    <?= $this->partial('tools-top') ?>
    <?php if (array_intersect(['create'], $actions)) { ?>
        <li><?= $this->inner('new') ?></li>
    <?php } ?>
    <?= $this->partial('tools-bottom') ?>
</ul>