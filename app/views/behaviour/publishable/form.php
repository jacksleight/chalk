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
		), 'Chalk\Core') ?>
		<?php $this->start() ?>
			<?= $this->render('/element/form-item', array(
				'entity'	=> $publishable,
				'name'		=> 'publishDate',
				'label'		=> 'Publish Date',
			), 'Chalk\Core') ?>
			<?= $this->render('/element/form-item', array(
				'entity'	=> $publishable,
				'name'		=> 'archiveDate',
				'label'		=> 'Archive Date',
			), 'Chalk\Core') ?>
		<?php $html = $this->end() ?>
		<?= $this->render('/element/expandable', [
			'content'		=> $html,
			'buttonLabel'	=> 'Advanced',
		], 'Chalk\Core') ?>
	</div>
</fieldset>