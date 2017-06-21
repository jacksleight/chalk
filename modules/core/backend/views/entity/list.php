<div class="flex-col">
    <div class="header">
        <?= $this->partial('tools') ?>
        <?= $this->partial('header') ?>
        <?php
        $filters = $this->partial('filters');
        ?>
        <?php if (strpos($filters, '</li>') !== false) { ?>
            <div class="hanging">
                <?= $filters ?>
            </div>
        <?php } ?>
    </div>
    <div class="flex body">
        <?= $this->partial($model->layout) ?>
    </div>
    <div class="footer">
        <?= $this->partial('selection') ?>
        <?php if ($entities instanceof Doctrine\ORM\Tools\Pagination\Paginator) { ?>
            <?= $this->partial('pagination') ?>
        <?php } ?>
    </div>
</div>