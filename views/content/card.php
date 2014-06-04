<div class="card">	
	<? if ($content instanceof \Ayre\Core\File && $content->file->exists() && $content->isGdCompatible()) { ?>
		<img src="<?= $this->image(
			$content->file,
			'resize',
			['size' => '48', 'crop' => true]
		) ?>">
	<? } ?>
	<?= $content->name ?><br>
	<small><?= $content->subname ?></small>
</div>