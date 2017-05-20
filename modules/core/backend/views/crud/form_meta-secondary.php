<ul class="meta meta-right">
	<?= $this->partial('meta-secondary-top') ?>
    <?php if (!$entity->isNew()) { ?>
        <li>
            <a href="<?= $this->url([
                'entityType' => $info->name,
                'entityId'   => $entity->id,
            ], 'core_frontend', true) ?>" target="_blank" class="icon-view">
                Open <?= $info->singular ?>
            </a>
        </li>
    <?php } ?>
	<?= $this->partial('meta-secondary-bottom') ?>
</ul>