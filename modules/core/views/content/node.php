<? if (isset($node)) { ?>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2><?= $node->structure->name ?></h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-item', array(
				'entity'		=> $node,
				'name'			=> 'isHidden',
				'label'			=> 'Hidden',
			)) ?>	
			<?= $this->render('/elements/form-item', array(
				'entity'		=> $node,
				'name'			=> 'name',
				'label'			=> 'Label',
				'placeholder'	=> $content->name,
				'note'			=> 'Text used in navigation and URLs',
			)) ?>
		</div>
	</fieldset>
<? } ?>