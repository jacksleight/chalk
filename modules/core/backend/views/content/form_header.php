<?= $this->partial('header-before') ?>
<h1>
	<?php if (!$content->isNew()) { ?>
		<?= $content->name ?>
	<?php } else { ?>
		New <?= $info->singular ?>
	<?php } ?>
</h1>
<?= $this->partial('header-after') ?>