<?php
use Chalk\App as Chalk;

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
			<div class="image" style="background-image: url('<?= $this->image($content->file, ['size' => '96']) ?>');"></div>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
		<?php } ?>
	</div>
	<strong><?= $content->name ?></strong>&nbsp;
	<?php
	$url = $this->frontend->url($content);
	?>
	<?php if ($url) { ?>
		<a href="<?= $url ?>" target="_blank" class="icon-view"></a>
	<?php } ?>
	<br>
	<small><?= $content->clarifier ?></small>
</div>