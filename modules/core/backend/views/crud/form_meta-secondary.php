<ul class="meta meta-right">
	<?= $this->partial('meta-secondary-top') ?>
	<?= $this->partial('meta-secondary-bottom') ?>
</ul>

<?php /* <ul class="meta meta-right">
    <?php if (!$entity->isNew()) { ?>
        <?php
        $url = $this->frontend->url($entity->getObject());
        ?>
        <?php if ($url) { ?>
            <li>
                <a href="<?= $url ?>" target="_blank" class="icon-view">
                    <?= $entity->status != Chalk::STATUS_PUBLISHED ? 'Preview' : 'View' ?> <?= $entity->subtype == 'mailto'
                        ? str_replace('Link', 'strongail Link', $info->singular)
                        : $info->singular ?>
                </a>
            </li>
        <?php } ?>
    <?php } ?>
</ul> */ ?>