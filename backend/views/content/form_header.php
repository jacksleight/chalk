<?= $this->partial('header-before') ?>
<h1>
	<?php if (!$content->isNewMaster()) { ?>
		<?= $content->name ?>
	<?php } else { ?>
		New <?= $info->singular ?>
	<?php } ?>
</h1>
<?= $this->partial('header-after') ?>