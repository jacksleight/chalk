<?php
use Chalk\App as Chalk;
$info = Chalk::info($widget);
?>
<div class="card">
	<div class="preview">
		<?php if ($widget instanceof \Chalk\Core\File && $widget->file->exists() && $widget->isImage()) { ?>
			<div class="image" style="background-image: url('<?= $this->image($widget->file, ['size' => '96']) ?>');"></div>
		<?php } else { ?>
			<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
		<?php } ?>
	</div>
	<strong><?= $info->singular ?></strong>&nbsp;
	<br>
	<small><?= implode(' – ', $widget->clarifier) ?></small>
</div>