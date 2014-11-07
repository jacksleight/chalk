<?php if (false && !$content->isNewMaster()) { ?>
	<li><a href="#" class="btn icon-history">
		View <?= $info->singular ?> History
	</a></li>
<?php } ?>
<?php if (!$content->isNewMaster() && $info->class != 'Chalk\Core\File') { ?>
	<li><a href="<?= $this->url([
			'entity'	=> $info->name,
			'action'	=> 'edit',
		], 'content', true) ?>" class="btn btn-focus btn-quiet icon-add">
		New <?= $info->singular ?>
	</a></li>
<?php } ?>