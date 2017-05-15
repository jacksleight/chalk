<?php
$template = isset($template) ? $template : false;
$covered  = isset($covered) ? $covered : false;
?>
<div class="card">
	<div class="preview">
		<?php if ($template) { ?>
			<div class="progress">
				<span style="height: 0%;"></span>
			</div>
		<?php } else { ?>
			<?php if ($entity instanceof \Chalk\Core\File && $entity->file->exists() && $entity->isImage()) { ?>
				<?php if ($entity->isImageBitmap()) { ?>
					<div class="image" style="background-image: url('<?= $this->image($entity->file, ['size' => '96']) ?>');"></div>
				<?php } else if ($entity->isImageVector()) { ?>
					<div class="image" style="background-image: url('<?= $this->frontend->url->file($entity->file) ?>');"></div>
				<?php } ?>
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
		<?= $template ? '{{name}}' : $entity->file->baseName() ?>&nbsp;
		<?php if (!$template) { ?>
			<a href="<?= $this->frontend->url->file($entity->file) ?>" target="_blank" class="icon-view"></a>
		<?php } ?>
		<br>
		<small>
			<?php if ($template) { ?>
				Waiting…
			<?php } else if ($entity->file->exists()) { ?>
				<?= \Coast\str_size_format($entity->file->size()) ?>
			<?php } else { ?>
				<span class="error">File not found, please reupload</span>
			<?php } ?>
		</small>
	</div>
</div>