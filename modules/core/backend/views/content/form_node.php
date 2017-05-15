<?php if (isset($node)) { ?>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Structure</h2>
			<p>These only apply when the <?= strtolower($info->singular) ?> is used in the <strong><?= $node->structure->name ?></strong> structure.</p>
		</div>
		<div class="form-items">
			<?= $this->render('/element/form-item', array(
				'entity'		=> $node,
				'name'			=> 'name',
				'label'			=> 'Label',
				'placeholder'	=> $entity->name,
				'note'			=> 'Text used in navigation and URLs',
			), 'core') ?>
			<?= $this->render('/element/form-item', array(
				'entity'		=> $node,
				'name'			=> 'isHidden',
				'label'			=> 'Hidden',
			), 'core') ?>	
		</div>
	</fieldset>
<?php } ?>