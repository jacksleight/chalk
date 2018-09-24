<ul class="meta meta-right">
	<?= $this->partial('meta-secondary-top') ?>
    <?php if (!$entity->isNew()) { ?>
        <li>
            <a href="<?= $this->url([
				'ref' => Chalk\Chalk::ref($entity, true),
            ], 'core_frontend', true) ?>" target="_blank" class="icon-view">
                Open
            </a>
        </li>
    <?php } ?>
	<?= $this->partial('meta-secondary-bottom') ?>
</ul>