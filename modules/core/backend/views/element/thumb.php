<?php
$template	= isset($template) ? $template : false;
$covered	= isset($covered) ? $covered : false;
if (!$template) {
	if (!isset($entity)) {
		return;
	}
	$info = isset($info) ? $info : Chalk\Chalk::info($entity);
}
?>
<figure class="thumb">
	<div class="preview">
		<?php if ($template) { ?>
			<div class="progress">
				<span style="height: 0%;"></span>
			</div>
		<?php } else { ?>
			<?php if ($entity->previewFile() !== null && $entity->previewFile()->exists()) { ?>
				<div class="image" style="background-image: url('<?= $this->image($entity->previewFile, ['size' => '400']) ?>');"></div>
			<?php } else { ?>
				<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
			<?php } ?>
			<?php if ($info->is->publishable) { ?>
				<span class="badge badge-upper badge-<?= $this->app->statusClass($entity->status) ?>">
					<?= $entity->status ?>
				</span>
			<?php } ?>
			<?php if ($covered) { ?>
				<div class="progress">
					<span style="height: 100%;"></span>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<figcaption>
		<?php if ($template) { ?>
			{{name}}<br>
			<small>Waiting…</small>
		<?php } else { ?>
			<?= $this->render('element/preview-text', ['entity' => $entity], 'core') ?>
		<?php } ?>
	</figcaption>
</figure>