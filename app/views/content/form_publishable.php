<? if (!$content->isProtected()) { ?>
	<?= $this->inner('/behaviour/publishable/form', ['publishable' => $content], 'core') ?>
<?php } ?>