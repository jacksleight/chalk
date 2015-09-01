<ul class="toolbar toolbar-right">
	<?= $this->partial('tools-top') ?>
	<?php if (false && !$content->isNewMaster()) { ?>
		<li><a href="#" class="btn icon-history">
			View <?= $info->singular ?> History
		</a></li>
	<?php } ?>
	<?php if (!$content->isNewMaster() && $info->class != 'Chalk\Core\File') { ?>
		<?php if (isset($node)) { ?>
			<? if ($info->class == 'Chalk\Core\Page') { ?>
				<li><a href="<?= $this->url([
						'action'	=> 'edit',
						'node'		=> null,
					]) ?><?= $this->url->query([
						'parent'	=> $node->id,
						'type'		=> $info->name,
					]) ?>" class="btn btn-focus btn-out icon-add">
					New <?= $info->singular ?>
				</a></li>
			<? } ?>
		<? } else { ?>
			<li><a href="<?= $this->url([
					'entity'	=> $info->name,
					'action'	=> 'edit',
				], 'content', true) ?>" class="btn btn-focus btn-out icon-add">
				New <?= $info->singular ?>
			</a></li>
		<? } ?>
	<?php } ?>
	<?= $this->partial('tools-bottom') ?>
</ul>