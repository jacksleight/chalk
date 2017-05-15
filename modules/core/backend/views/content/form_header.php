<?= $this->partial('header-before') ?>
<h1>
	<?php if (!$entity->isNew()) { ?>
		<?= $entity->name ?>
	<?php } else { ?>
		New <?= $info->singular ?>
	<?php } ?>
</h1>
<?= $this->partial('header-after') ?>