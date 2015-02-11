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
	<?php if ($isEditAllowed) { ?>
		</a>
	<?php } ?>
</figure>