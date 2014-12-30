<?php
$template	= isset($template) ? $template : false;
$covered	= isset($covered) ? $covered : false;
$link		= isset($link) ? $link : true;
?>

<figure class="thumb selectable">
	<?php if ($link) { ?>
		<a href="<?= !$template ? $this->url([
			'entity'=> $info->name,
			'action'	=> 'edit',
			'content'	=> $content->id,
		]) : '#' ?>">
	<?php } ?>
		<?php if (!$template) { ?>
			<?= $this->child('/content/checkbox', [
				'value'	=> $content,
			]) ?>
		<?php } ?>
		<div class="preview">
			<?php if ($template) { ?>
				<div class="progress">
					<span style="height: 0%;"></span>
				</div>
			<?php } else { ?>
				<?php if ($content instanceof \Chalk\Core\File && $content->file->exists() && $content->isGdCompatible()) { ?>
					<img src="<?= $this->image(
						$content->file,
						'resize',
						['size' => '400', 'crop' => true]
					) ?>">
				<?php } else { ?>
					<div class="text"><span class="icon-content-dark"></span></div>
				<?php } ?>
				<span class="badge badge-status badge-<?= $content->status ?>">
					<?= $content->status ?>
				</span>
				<?php if ($covered) { ?>
					<div class="progress">
						<span style="height: 100%;"></span>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<figcaption>
			<?= $template ? '{{name}}' : $content->name ?><br>
			<small>
				<?php if ($template) { ?>
					Waiting…
				<?php } else { ?>
					<?= $content->subname($info->class != 'Chalk\Core\Content') ?>
				<?php } ?>
			</small>
		</figcaption>
	<?php if ($link) { ?>
		</a>
	<?php } ?>
</figure>