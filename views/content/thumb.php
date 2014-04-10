<?php
$template = isset($template) && $template;
$covered  = isset($covered) && $covered;
?>
<li>
	<a href="<?= !$template ? $this->url([
		'action'	=> 'edit',
		'id'		=> $content->id,
	]) : '#' ?>">
		<figure class="thumb">
			<div class="preview">
				<? if ($template) { ?>
					<div class="progress"><span style="height: 0%;"></span></div>
				<? } else { ?>
					<? if ($content instanceof \Ayre\Entity\File && $content->mimeType == 'image/jpeg') { ?>
						<img src="<?= $this->url($this->image($content->file, 'resize', ['size' => '400', 'crop' => true])) ?>">
					<? } else if ($content instanceof \Ayre\Entity\File) { ?>
						<div class="text"><span><?= $content->extName ?></span></div>
					<? } ?>
					<span class="label status status-<?= $content->status ?>"><?= $content->status ?></span>
					<? if ($covered) { ?>
						<div class="progress"><span style="height: 100%;"></span></div>
					<? } ?>
				<? } ?>
			</div>
			<figcaption>
				<strong class="name"><?= $template ? '{{name}}' : $content->name ?></strong><br>
				<? if ($template) { ?>
					Waiting…
				<? } else if ($content instanceof \Ayre\Entity\File) { ?>
					<span class="info"><?= isset($this->mimeTypes[$content->mimeType])
						? $this->mimeTypes[$content->mimeType]
						: $content->mimeType ?></span>
				<? } ?>
			</figcaption>
		</figure>
	</a>
</li>