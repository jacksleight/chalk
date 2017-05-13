<ul class="toolbar">
	<?php if (!$content->isProtected()) { ?>		
		<?php if (!$content->isArchived() && !$content->isNew()) { ?>
			<li><a href="<?= $this->url([
				'action'	=> 'archive',
			]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-lighter btn-out confirmable icon-archive">
				Archive <?= $info->singular ?>
			</a></li>
		<?php } ?>
		<?php if (!isset($node) && $content->isArchived()) { ?>
			<li><a href="<?= $this->url([
				'action'	=> 'delete',
			]) ?>?redirect=<?= $this->url([]) ?>" class="btn btn-negative btn-out confirmable icon-delete" data-message="Are you sure?<?= "\n" ?>This will delete the <?= strtolower($info->singular) ?> and cannot be undone.">
				Delete <?= $info->singular ?>
			</a></li>
		<?php } ?>
	<?php } ?>
	<?php if (isset($node) && !$node->isRoot()) { ?>
		<li><a href="<?= $this->url([
			'action' => 'delete'
		]) ?>" class="btn btn-lighter btn-out confirmable icon-remove">
			Remove <?= $info->singular ?> <small>from <strong><?= $structure->name ?></strong></small>
		</a></li>
	<?php } ?>
</ul>