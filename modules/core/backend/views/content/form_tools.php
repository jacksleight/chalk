<ul class="toolbar toolbar-right">
	<?= $this->partial('tools-top') ?>
	<?php if (false && !$content->isNewMaster()) { ?>
		<li><a href="#" class="btn icon-history">
			View <?= $info->singular ?> History
		</a></li>
	<?php } ?>
	<?php if (!$content->isNewMaster() && $info->class != 'Chalk\Core\File') { ?>
		<?php if (isset($node)) { ?>
			<? if (is_a($info->class, 'Chalk\Core\Page', true)) { ?>
				<li><a href="<?= $this->url([
						'action'	=> 'edit',
						'node'		=> null,
					]) ?><?= $this->url->query([
						'parent'	=> $node->id,
						'type'		=> $info->name,
					]) ?>" class="btn btn-focus btn-out icon-add">
					New Child <?= $info->singular ?>
				</a></li>
				<?php if (isset($node->parent)) { ?>
					<li><a href="<?= $this->url([
							'action'	=> 'edit',
							'node'		=> null,
						]) ?><?= $this->url->query([
							'parent'	=> $node->parent->id,
							'type'		=> $info->name,
						]) ?>" class="btn btn-focus btn-out icon-add">
						New Sibling <?= $info->singular ?>
					</a></li>
				<?php } ?>
			<? } ?>
		<? } else { ?>
			<li><a href="<?= $this->url([
					'entity'	=> $info->name,
					'action'	=> 'edit',
				], 'core_content', true) ?>" class="btn btn-focus btn-out icon-add">
				New <?= $info->singular ?>
			</a></li>
		<? } ?>
	<?php } ?>
	<?= $this->partial('tools-bottom') ?>
</ul>