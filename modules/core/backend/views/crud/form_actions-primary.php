<ul class="toolbar toolbar-right">
    <?= $this->partial('actions-primary-top') ?>
    <?php if (is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true)) { ?>
        <?php if (!$entity->isArchived()) { ?>
            <li><button class="btn btn-positive icon-ok">
                Save <?= $info->singular ?>
            </button></li>
        <?php } else { ?>
            <li><a href="<?= $this->url([
                'action' => 'restore',
            ]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-positive icon-restore">
                Restore <?= $info->singular ?>
            </a></li>
        <?php } ?>
    <?php } else { ?>
        <li><button class="btn btn-positive icon-ok">
            Save <?= $info->singular ?>
        </button></li>
    <?php } ?>
    <?= $this->partial('actions-primary-top') ?>
</ul>