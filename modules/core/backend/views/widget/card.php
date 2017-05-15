<?php
use Chalk\Chalk;
$info = Chalk::info($widget);
$file = $widget->previewFile;
?>
<div class="card">
	<div class="preview">
		<?php if (isset($file) && $file->exists() && \Chalk\is_image($file)) { ?>
			<div class="image" style="background-image: url('<?= $this->image($file, ['size' => '96']) ?>');"><i class="remove">&nbsp;</i></div>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"><i class="remove">&nbsp;</i></span></div>
		<?php } ?>
	</div>
	<div class="card-inner">
		<strong><?= $info->singular ?></strong>&nbsp;
		<br>
		<small><?= implode(' – ', $widget->previewText) . '&nbsp;' ?></small>
	</div>
</div>