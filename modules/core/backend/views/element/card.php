<?php
if (isset($ref)) {
	$entity = $this->em($ref['type'])->id($ref['id']);
	if (isset($entity)) {
		$sub = $entity->sub($ref['sub']);
	}
}
if (isset($entity)) {
	$info = Chalk\Chalk::info($entity);
} else if (isset($ref)) {
	$info = Chalk\Chalk::info($ref['type']);
} else if (isset($scope)) {
	$info = $scope;
}
?>
<div class="card">
	<? if (isset($entity)) { ?>
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
				'ref' => Chalk\Chalk::ref(isset($ref) ? $ref : $entity, true),
			], 'core_frontend', true) ?>" target="_blank" class="icon-view"></a>
			<br>
			<small><?= $this->strip(implode(' – ', $entity->previewText())) ?></small>
		</div>
	<? } else { ?>
		<div class="preview">
			<?php if (isset($info)) { ?>
				<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
			<?php } else { ?>
				<div class="text"><span class="icon-<?= $info->cross ?>"></span></div>
			<?php } ?>
		</div>
		<div class="card-inner">
			Deleted <?= isset($info) ? $info->singular : 'entity' ?>
			<br>
			<span class="error"><?= isset($info) ? $info->singular : 'Entity' ?> has been deleted, please select another</span>
		</div>
		
	<? } ?>
</div>