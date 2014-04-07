<?php
$t = isset($template) && $template;
?>
<li>
	<figure class="thumb">
		<div class="preview">
			<? if ($t) { ?>
				<div class="progress"><span class="status" style="height: 0%;"></span></div>
			<? } else { ?>
				<? if ($file->mimeType == 'image/jpeg') { ?>
					<img src="<?= $this->url($this->image->lorempixel(200)) ?>">
				<? } else { ?>
					<div class="text"><span><?= $file->extName ?></span></div>
				<? } ?>
			<? } ?>
		</div>
		<figcaption>
			<strong class="name"><?= $t ? '{{name}}' : $file->name ?></strong><br>
			<span class="info"><?= $t ? 'Waiting…' : $this->mimeTypes[$file->mimeType] ?></span>
		</figcaption>
	</figure>
</li>