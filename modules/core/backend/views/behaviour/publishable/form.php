<fieldset class="form-block">
	<div class="form-legend">
		<h2>Publish</h2>
	</div>
	<div class="form-items">
		<?= $this->render('/element/form-item', array(
			'type'	    => 'input_radio',
			'entity'	=> $publishable,
			'name'		=> 'status',
			'label'		=> 'Status',
		), 'core') ?>
		<?php $this->start() ?>
			<?= $this->render('/element/form-item', array(
				'entity'	=> $publishable,
				'name'		=> 'publishDate',
				'label'		=> 'Publish Date',
			), 'core') ?>
			<?= $this->render('/element/form-item', array(
				'entity'	=> $publishable,
				'name'		=> 'archiveDate',
				'label'		=> 'Archive Date',
			), 'core') ?>
		<?php $html = $this->end() ?>
		<?= $this->render('/element/expandable', [
			'content'		=> $html,
			'buttonLabel'	=> '',
		], 'core') ?>
	</div>
</fieldset>