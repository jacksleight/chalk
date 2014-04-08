<?php
$template = isset($template) && $template;
$covered  = isset($covered) && $covered;
?>
<li>
	<figure class="thumb">
		<div class="preview">
			<? if ($template) { ?>
				<div class="progress"><span class="status" style="height: 0%;"></span></div>
			<? } else { ?>
				<? if ($file->mimeType == 'image/jpeg') { ?>
					<img src="<?= $this->url($this->image->lorempixel(200)) . '?' . rand() ?>">
				<? } else { ?>
					<div class="text"><span><?= $file->extName ?></span></div>
				<? } ?>
				<? if ($covered) { ?>
					<div class="progress"><span class="status" style="height: 100%;"></span></div>
				<? } ?>
			<? } ?>
		</div>
		<figcaption>
			<strong class="name"><?= $template ? '{{name}}' : $file->name ?></strong><br>
			<span class="info"><?= $template ? 'Waiting…' : $this->mimeTypes[$file->mimeType] ?></span>
		</figcaption>
	</figure>
</li>