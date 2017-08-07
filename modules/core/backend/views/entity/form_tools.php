<ul class="toolbar toolbar-right">
	<?= $this->partial('tools-top') ?>
    <?php if ($info->is->duplicateable) { ?>
        <li><a href="<?= $this->url([
                'action'    => 'duplicate',
            ]) ?>" class="btn btn-focus btn-out icon-copy confirmable">
            Duplicate
        </a></li>
    <?php } ?>
    <?php if (array_intersect(['create'], $actions) && !$entity->isNew()) { ?>
        <li><?= $this->inner('new', ['class' => 'btn-out']) ?></li>
    <?php } ?>
	<?= $this->partial('tools-bottom') ?>
</ul>