<ul class="meta">
	<?= $this->partial('meta-primary-top') ?>
	<li class="icon-<?= $info->icon ?>">
		<?= $info->singular ?>
	</li>
    <?php if (is_a($info->class, 'Chalk\Core\Behaviour\Trackable', true) && !$entity->isNew()) { ?>
        <li class="icon-updated">
            Updated <strong><?= $entity->modifyDate->diffForHumans() ?></strong>
            by <strong><?= $entity->modifyUserName ?></strong>
        </li>
    <?php } ?>
	<?= $this->partial('meta-primary-bottom') ?>
</ul>