<?php if (!$content->isProtected()) { ?>
	<?= $this->render('/behaviour/publishable/form', ['publishable' => $content], 'core') ?>
<?php } ?>