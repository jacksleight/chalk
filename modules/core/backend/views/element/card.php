<?php
if (!isset($entity)) {
	return;
}
$info = isset($info) ? $info : Chalk\Chalk::info($entity);
?>
<div class="card">
	<div class="preview">
		<?php if ($entity->previewFile !== null && $entity->previewFile->exists()) { ?>
			<div class="image" style="background-image: url('<?= $this->image($entity->file, ['size' => '96']) ?>');"></div>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
		<?php } ?>
	</div>
	<div class="card-inner">
		<?= $entity->previewName() ?>
		<a href="<?= $this->url([
            'entityType' => $info->name,
            'entityId'   => $entity->id,
        ], 'core_frontend', true) ?>" target="_blank" class="icon-view"></a>
		<br>
		<small><?= implode(' – ', $entity->previewText()) ?></small>
	</div>
</div>