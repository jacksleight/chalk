<ul class="toolbar toolbar-right">
	<?= $this->partial('tools-top') ?>
    <?php if ($info->is->duplicateable) { ?>
        <li><a href="<?= $this->url([
                'action'    => 'duplicate',
            ]) ?>" class="btn btn-focus btn-out icon-copy">
            Duplicate
        </a></li>
    <?php } ?>
    <?php if (array_intersect(['create'], $actions) && !$entity->isNew()) { ?>
        <li><a href="<?= $this->url([
                'action'    => 'update',
                'id'        => null,
            ]) ?>" class="btn btn-focus btn-out icon-add">
            New <?= $info->singular ?>
        </a></li>
    <?php } ?>
	<?= $this->partial('tools-bottom') ?>
</ul>