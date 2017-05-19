<?php
$template		= isset($template) ? $template : false;
$covered		= isset($covered) ? $covered : false;
$isEditAllowed	= isset($isEditAllowed) ? $isEditAllowed : true;
?>

<figure class="thumb selectable">
	<?php if ($isEditAllowed) { ?>
		<a href="<?= !$template ? $this->url([
            'action' => 'update',
            'id'     => $entity->id,
		]) . $this->url->query(['tagsList' => $index->tagsList]) : '#' ?>">
	<?php } ?>
		<?php if (!$template && !$covered) { ?>
			<?= $this->partial('checkbox', [
				'entity'	=> $entity,
				'selected'	=> $index->selected,
            ]) ?>
		<?php } ?>
		<div class="preview">
			<?php if ($template) { ?>
				<div class="progress">
					<span style="height: 0%;"></span>
				</div>
			<?php } else { ?>
				<?php if ($entity->previewFile() !== null && $entity->previewFile()->exists()) { ?>
					<div class="image" style="background-image: url('<?= $this->image($entity->file, ['size' => '400']) ?>');"></div>
				<?php } else { ?>
					<div class="text"><span class="icon-<?= $info->icon ?>"></span></div>
				<?php } ?>
				<?php if (is_a($info->class, 'Chalk\Core\Behaviour\Publishable', true)) { ?>
					<span class="badge badge-upper badge-<?= $this->app->statusClass($entity->status) ?>">
						<?= $entity->status ?>
					</span>
				<?php } ?>
				<?php if ($covered) { ?>
					<div class="progress">
						<span style="height: 100%;"></span>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
		<figcaption>
			<?php if ($template) { ?>
				{{name}}<br>
				<small>Waiting…</small>
			<?php } else { ?>
				<?= $entity->previewName() ?><br>
				<small><?= implode(' – ', $entity->previewText(true)) ?></small>
			<?php } ?>
		</figcaption>
	<?php if ($isEditAllowed) { ?>
		</a>
	<?php } ?>
</figure>