<?php
if (!isset($content)) {
	return;
}
?>
<div class="card">	
	<?php if ($content instanceof \Chalk\Core\File && $content->file->exists() && $content->isImage()) { ?>
		<img src="<?= $this->image(
			$content->file,
			'resize',
			['size' => '48', 'crop' => true]
		) ?>">
	<?php } ?>
	<strong><?= $content->name ?></strong><br>
	<small><?= $content->clarifier ?></small>
</div>