<?php
$template		= isset($template) ? $template : false;
$covered		= isset($covered) ? $covered : false;
$isEditAllowed	= isset($isEditAllowed) ? $isEditAllowed : true;
?>

<figure class="thumb selectable">
	<?php if ($isEditAllowed) { ?>
		<a href="<?= !$template ? $this->url([
			'entity'=> $info->name,
			'action'	=> 'edit',
			'content'	=> $content->id,
		]) : '#' ?>">
	<?php } ?>
		<?php if (!$template) { ?>
			<?= $this->render('/behaviour/selectable/checkbox', [
                'entity'   => $content,
                'entities' => isset($index) ? $index->contents : new \Doctrine\Common\Collections\ArrayCollection(),
            ], 'core') ?>
		<?php } ?>
		<div class="preview">
			<?php if ($template) { ?>
				<div class="progress">
					<span style="height: 0%;"></span>
				</div>
			<?php } else { ?>
				<?php if ($content instanceof \Chalk\Core\File && $content->file->exists() && $content->isImage()) { ?>
					<div class="image" style="background-image: url('<?= $this->image($content->file, ['size' => '400']) ?>');"></div>
				<?php } else { ?>
					<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
				<?php } ?>
				<span class="badge badge-upper badge-<?= $this->app->statusClass($content->status) ?>">
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
					<?= implode(' – ', $content->previewText($info->class != 'Chalk\Core\Content')) ?>
				<?php } ?>
			</small>
		</figcaption>
	<?php if ($isEditAllowed) { ?>
		</a>
	<?php } ?>
</figure>