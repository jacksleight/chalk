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
			<?php if ($content instanceof \Chalk\Core\File && $content->file->exists() && $content->isImage()) { ?>
				<div class="image" style="background-image: url('<?= $this->image($content->file, ['size' => '400']) ?>');"></div>
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
		<?= $template ? '{{name}}' : $content->file->baseName() ?>&nbsp;
		<?php if (!$template) { ?>
			<a href="<?= $this->frontend->url->file($content->file) ?>" target="_blank" class="icon-view"></a>
		<?php } ?>
		<br>
		<small>
			<?php if ($template) { ?>
				Waiting…
			<?php } else { ?>
				<?= \Coast\str_size_format($content->file->size()) ?>
			<?php } ?>
		</small>
	</div>
</div>