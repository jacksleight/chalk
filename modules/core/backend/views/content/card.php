<?php
use Chalk\Chalk;

if (!isset($entity)) {
	return;
}
$info = isset($info)
	? $info
	: Chalk::info($entity);
?>
<div class="card">
	<div class="preview">
		<?php if ($entity instanceof \Chalk\Core\File && $entity->file->exists() && $entity->isImage()) { ?>
			<?php if ($entity->isImageBitmap()) { ?>
				<div class="image" style="background-image: url('<?= $this->image($entity->file, ['size' => '96']) ?>');"></div>
			<?php } else if ($entity->isImageVector()) { ?>
				<div class="image" style="background-image: url('<?= $this->frontend->url->file($entity->file) ?>');"></div>
			<?php } ?>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
		<?php } ?>
	</div>
	<div class="card-inner">
		<strong><?= $entity->name ?></strong>&nbsp;
		<?php
		$url = $this->frontend->url($entity);
		?>
		<?php if ($url) { ?>
			<a href="<?= $url ?>" target="_blank" class="icon-view"></a>
		<?php } ?>
		<br>
		<small><?= implode(' – ', $entity->previewText) ?></small>
	</div>
</div>