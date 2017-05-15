<?php
use Chalk\Chalk;

if (!isset($content)) {
	return;
}
$info = isset($info)
	? $info
	: Chalk::info($content);
?>
<div class="card">
	<div class="preview">
		<?php if ($content instanceof \Chalk\Core\File && $content->file->exists() && $content->isImage()) { ?>
			<?php if ($content->isImageBitmap()) { ?>
				<div class="image" style="background-image: url('<?= $this->image($content->file, ['size' => '96']) ?>');"></div>
			<?php } else if ($content->isImageVector()) { ?>
				<div class="image" style="background-image: url('<?= $this->frontend->url->file($content->file) ?>');"></div>
			<?php } ?>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
		<?php } ?>
	</div>
	<div class="card-inner">
		<strong><?= $content->name ?></strong>&nbsp;
		<?php
		$url = $this->frontend->url($content);
		?>
		<?php if ($url) { ?>
			<a href="<?= $url ?>" target="_blank" class="icon-view"></a>
		<?php } ?>
		<br>
		<small><?= implode(' – ', $content->previewText) ?></small>
	</div>
</div>