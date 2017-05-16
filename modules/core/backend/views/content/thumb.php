<?php
$template		= isset($template) ? $template : false;
$covered		= isset($covered) ? $covered : false;
$isEditAllowed	= isset($isEditAllowed) ? $isEditAllowed : true;
?>

<figure class="thumb selectable">
	<?php if ($isEditAllowed) { ?>
		<a href="<?= !$template ? $this->url([
            'entity' => $info->name,
            'action' => 'update',
            'id'     => $entity->id,
		]) : '#' ?>">
	<?php } ?>
		<?php if (!$template) { ?>
			<?= $this->partial('checkbox', [
                'entity'   => $entity,
                'entities' => isset($index) ? $index->contents : new \Doctrine\Common\Collections\ArrayCollection(),
            ]) ?>
		<?php } ?>
		<div class="preview">
			<?php if ($template) { ?>
				<div class="progress">
					<span style="height: 0%;"></span>
				</div>
			<?php } else { ?>
				<?php if ($entity instanceof \Chalk\Core\File && $entity->file->exists() && $entity->isImage()) { ?>
					<?php if ($entity->isImageBitmap()) { ?>
						<div class="image" style="background-image: url('<?= $this->image($entity->file, ['size' => '400']) ?>');"></div>
					<?php } else if ($entity->isImageVector()) { ?>
						<div class="image" style="background-image: url('<?= $this->frontend->url->file($entity->file) ?>');"></div>
					<?php } ?>
				<?php } else { ?>
					<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
				<?php } ?>
				<span class="badge badge-upper badge-<?= $this->app->statusClass($entity->status) ?>">
					<?= $entity->status ?>
				</span>
				<?php if ($covered) { ?>
					<div class="progress">
						<span style="height: 100%;"></span>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<figcaption>
			<?= $template ? '{{name}}' : $entity->name ?><br>
			<small>
				<?php if ($template) { ?>
					Waiting…
				<?php } else { ?>
					<?= implode(' – ', $entity->previewText($info->class != 'Chalk\Core\Content')) ?>
				<?php } ?>
			</small>
		</figcaption>
	<?php if ($isEditAllowed) { ?>
		</a>
	<?php } ?>
</figure>