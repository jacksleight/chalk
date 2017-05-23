<h1>
	<?php if (!$entity->isNew()) { ?>
		<?= $entity->previewName ?>
	<?php } else { ?>
		New <?= $info->singular ?>
	<?php } ?>
</h1>