<?php
$template = isset($template) ? $template : false;
$covered  = isset($covered)  ? $covered  : false;
?>
<div class="card">
	<div class="preview">
		<?php if ($template) { ?>
			<div class="progress">
				<span style="height: 0%;"></span>
			</div>
		<?php } else { ?>
			<?php if ($entity->previewFile() !== null && $entity->previewFile()->exists()) { ?>
				<div class="image" style="background-image: url('<?= $this->image($entity->previewFile, ['size' => '96']) ?>');"></div>
			<?php } else { ?>
				<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
			<?php } ?>
			<?php if ($covered) { ?>
				<div class="progress">
					<span style="height: 100%;"></span>
				</div>
			<?php } ?>
		<?php } ?>
	</div>
	<div class="card-inner">
		<?php if ($template) { ?>
			{{name}}<br>
			<small>Waiting…</small>
		<?php } else { ?>
			<?= $entity->file->baseName() ?>
			<?php if (!$template) { ?>
				<a href="<?= $this->frontendUrl->file($entity->file) ?>" target="_blank" class="icon-view"></a>
			<?php } ?>
			<br>
			<?php if ($entity->file->exists()) { ?>
				<small><?= \Coast\str_size_format($entity->file->size()) ?></small>
			<?php } else { ?>
				<span class="error">File not found, please upload again</span>
			<?php } ?>
		<?php } ?>
	</div>
</div>