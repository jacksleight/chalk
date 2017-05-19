<ul class="toolbar">
    <?= $this->partial('actions-secondary-top') ?>
    <?php if ($isDeleteAllowed && !$entity->isNew()) { ?>
        <?php if (is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true)) { ?>
            <?php if ($entity->isArchived() ) { ?>
                <li><a href="<?= $this->url([
                    'action'    => 'delete',
                ]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-negative btn-out confirmable icon-delete" data-message="Are you sure?">
                    Delete <?= $info->singular ?>
                </a></li>
            <?php } else { ?>
                <li><a href="<?= $this->url([
                    'action'    => 'archive',
                ]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-lighter btn-out confirmable icon-archive">
                    Archive <?= $info->singular ?>
                </a></li>
            <?php } ?>
        <?php } else { ?>
            <li><a href="<?= $this->url([
                'action'    => 'delete',
            ]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-negative btn-out confirmable icon-delete" data-message="Are you sure?">
                Delete <?= $info->singular ?>
            </a></li>
        <?php } ?>
    <?php } ?>
    <?= $this->partial('actions-secondary-bottom') ?>
</ul>

<?php /* if (isset($node) && !$node->isRoot()) { ?>
    <li><a href="<?= $this->url([
        'action' => 'delete'
    ]) ?>" class="btn btn-lighter btn-out confirmable icon-remove">
        Remove <?= $info->singular ?> <small>from <strong><?= $structure->name ?></strong></small>
    </a></li>
<?php } */ ?>