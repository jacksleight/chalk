<?php /* if (!$entity->isNew() && $info->class != 'Chalk\Core\File') { ?>
	<?php if (isset($node)) { ?>
		<?php if (is_a($info->class, 'Chalk\Core\Page', true)) { ?>
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
		<?php } ?>
	<?php } ?>
<?php } */ ?>