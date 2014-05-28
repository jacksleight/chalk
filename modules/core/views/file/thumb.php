<?php
$template = isset($template) && $template;
$covered  = isset($covered)  && $covered;
?>
<li>
	<a href="<?= !$template ? $this->url([
		'entityType'=> $entityType->slug,
		'action'	=> 'edit',
		'content'	=> $content->id,
	]) : '#' ?>">
		<figure class="thumb selectable">
			<? if (!$template) { ?>
				<input type="checkbox" id="entity-<?= $content->id ?>"><label for="entity-<?= $content->id ?>"></label>
			<? } ?>
			<div class="preview">
				<? if ($template) { ?>
					<div class="progress">
						<span style="height: 0%;"></span>
					</div>
				<? } else { ?>
					<? if ($content->file->exists() && $content->isGdCompatible()) { ?>
						<img src="<?= $this->image(
							$content->file,
							'resize',
							['size' => '400', 'crop' => true]
						) ?>">
					<? } else { ?>
						<div class="text"><span><i class="fa fa-file"></i></span></div>
					<? } ?>
					<span class="badge badge-status badge-<?= $content->status ?>">
						<?= $content->status ?>
					</span>
					<? if ($covered) { ?>
						<div class="progress">
							<span style="height: 100%;"></span>
						</div>
					<? } ?>
				<? } ?>
			</div>
			<figcaption>
				<strong class="name">
					<?= $template ? '{{name}}' : $content->name ?>
				</strong><br>
				<? if ($template) { ?>
					Waiting…
				<? } else { ?>
					<span class="info">
						<?= $content->subtypeLabel ?>
					</span>
				<? } ?>
			</figcaption>
		</figure>
	</a>
</li>