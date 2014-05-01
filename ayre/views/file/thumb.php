<?php
$template = isset($template) && $template;
$covered  = isset($covered)  && $covered;
?>
<li>
	<a href="<?= !$template ? $this->url([
		'entityType'=> $entityType->slug,
		'action'	=> 'edit',
		'id'		=> $entity->id,
	]) : '#' ?>">
		<figure class="thumb">
			<input type="checkbox">
			<div class="preview">
				<? if ($template) { ?>
					<div class="progress">
						<span style="height: 0%;"></span>
					</div>
				<? } else { ?>
					<? if ($entity->file->exists() && $entity->isGdCompatible()) { ?>
						<img src="<?= $this->image(
							$entity->file,
							'resize',
							['size' => '400', 'crop' => true]
						) ?>">
					<? } else { ?>
						<div class="text"><span><?= $entity->typeExtName ?></span></div>
					<? } ?>
					<span class="label label-status-<?= $entity->status ?>">
						<?= $entity->status ?>
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
					<?= $template ? '{{name}}' : $entity->name ?>
				</strong><br>
				<? if ($template) { ?>
					Waiting…
				<? } else { ?>
					<span class="info">
						<?= $entity->type ?>
					</span>
				<? } ?>
			</figcaption>
		</figure>
	</a>
</li>