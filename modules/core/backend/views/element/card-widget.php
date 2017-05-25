<?php
if (!isset($widget)) {
	return;
}
$info = isset($info) ? $info : Chalk\Chalk::info($widget);
?>
<div class="card">
	<div class="preview">
		<?php if ($widget->previewFile !== null && $widget->previewFile->exists()) { ?>
			<div class="image" style="background-image: url('<?= $this->image($widget->previewFile, ['size' => '96']) ?>');"><i class="remove">&nbsp;</i></div>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"><i class="remove">&nbsp;</i></span></div>
		<?php } ?>
	</div>
	<div class="card-inner">
		<?= $widget->previewName() ?>&nbsp;
		<br>
		<small><?= implode(' – ', $widget->previewText()) ?>&nbsp;</small>
	</div>
</div>