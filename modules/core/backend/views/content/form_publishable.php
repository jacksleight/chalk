<?php if (!$entity->isProtected()) { ?>
	<?= $this->render('/behaviour/publishable/form', ['publishable' => $entity], 'core') ?>
<?php } ?>