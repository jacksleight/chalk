<ul class="toolbar">
    <?= $this->partial('actions-secondary-top') ?>
    <?php if (array_intersect(['delete'], $actions) && !$entity->isNew()) { ?>
        <?php if ($info->is->publishable) { ?>
            <?php if ($entity->isPublished()) { ?>
                <li><a href="<?= $this->url([
                    'action'    => 'archive',
                ]) ?>" class="btn btn-lighter btn-out icon-archive">
                    Archive
                </a></li>
            <?php } else { ?>
                <li><a href="<?= $this->url([
                    'action'    => 'delete',
                ]) ?>" class="btn btn-negative btn-out confirmable icon-delete" data-message="Are you sure?">
                    Delete
                </a></li>
            <?php } ?>
        <?php } else { ?>
            <li><a href="<?= $this->url([
                'action'    => 'delete',
            ]) ?>" class="btn btn-negative btn-out confirmable icon-delete" data-message="Are you sure?">
                Delete
            </a></li>
        <?php } ?>
    <?php } ?>
    <?= $this->partial('actions-secondary-bottom') ?>
</ul>