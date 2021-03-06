<ul class="toolbar toolbar-right">
    <?= $this->partial('actions-primary-top') ?>
    <?php if (array_intersect(['update'], $actions)) { ?>
        <?php if ($info->is->publishable) { ?>
            <?php if (!$entity->isNew() && $entity->isDraft()) { ?>
                <li><a href="<?= $this->url([
                    'action' => 'publish',
                ]) ?>" class="btn btn-positive btn-lighter btn-out icon-publish">
                    Publish
                </a></li>
            <?php } ?>
            <?php if (!$entity->isArchived()) { ?>
                <li><button class="btn btn-positive icon-ok">
                    Save Changes
                </button></li>
            <?php } else { ?>
                <li><a href="<?= $this->url([
                    'action' => 'restore',
                ]) ?>" class="btn btn-positive icon-restore">
                    Restore to Draft
                </a></li>
            <?php } ?>
        <?php } else { ?>
            <li><button class="btn btn-positive icon-ok">
                Save <?= $info->singular ?>
            </button></li>
        <?php } ?>
    <?php } ?>
    <?= $this->partial('actions-primary-top') ?>
</ul>