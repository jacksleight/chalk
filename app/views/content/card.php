<?php
if (!isset($content)) {
	return;
}
?>
<div class="card">	
	<?php if ($content instanceof \Chalk\Core\File && $content->file->exists() && $content->isGdCompatible()) { ?>
		<img src="<?= $this->image(
			$content->file,
			'resize',
			['size' => '48', 'crop' => true]
		) ?>">
	<?php } ?>
	<strong><?= $content->name ?></strong><br>
	<small><?= $content->subname() ?></small>
</div>