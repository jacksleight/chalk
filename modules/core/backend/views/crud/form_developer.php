<fieldset class="form-block">
	<div class="form-legend">
		<h2>Developer</h2>
	</div>
	<div class="form-items">
		<?= $this->partial('developer-top') ?>
		<?= $this->partial('developer-bottom') ?>
		<?php $this->start() ?>
			<?= $this->render('/element/form-item', [
				'entity'		=> $entity,
				'name'			=> 'id',
				'label'			=> 'ID',
				'type'			=> 'input_pseudo',
				'readOnly'		=> true,
			], 'core') ?>
			<?= $this->partial('developer-information') ?>
		<?php $html = $this->end() ?>
		<?= $this->render('/element/expandable', [
			'content'		=> $html,
			'buttonLabel'	=> 'Information',
		], 'core') ?>
	</div>
</fieldset>