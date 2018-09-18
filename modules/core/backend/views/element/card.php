<?php
if (!isset($entity)) {
	return;
}
$info = isset($info) ? $info : Chalk\Chalk::info($entity);
?>
<div class="card">
	<div class="preview">
		<?php if ($entity->previewFile !== null && $entity->previewFile->exists()) { ?>
			<div class="image" style="background-image: url('<?= $this->image($entity->previewFile, ['size' => '96']) ?>');"></div>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
		<?php } ?>
	</div>
	<div class="card-inner">
		<?= $entity->previewName() ?>
		<? if (isset($sub)) { ?>
			<i class="icon-arrow-right2 icon-small"></i>
			<?= $this->render('/element/sub-text', ['sub' => $sub]) ?>
		<? } ?>
		<a href="<?= $this->url([
            'entityType' => $info->name,
            'entityId'   => $entity->id,
            'entitySub'  => isset($sub) ? Chalk\Chalk::subToString($sub) : null,
        ], 'core_frontend', true) ?>" target="_blank" class="icon-view"></a>
		<br>
		<small><?= $this->strip(implode(' – ', $entity->previewText())) ?></small>
	</div>
</div>