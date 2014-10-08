<h1>
	<?php if (!$content->isNewMaster()) { ?>
		<?= $content->name ?>
	<?php } else { ?>
		New <?= $entity->singular ?>
	<?php } ?>
</h1>