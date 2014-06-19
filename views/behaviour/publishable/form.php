<fieldset class="form-block">
	<div class="form-legend">
		<h2>Publish</h2>
	</div>
	<div class="form-items">
		<?= $this->render('/elements/form-item', array(
			'type'	    => 'input_radio',
			'entity'	=> $publishable,
			'name'		=> 'status',
			'label'		=> 'Status',
			'disabled'	=> $publishable->isArchived(),
		), 'core') ?>
		<div class="expandable">
			<div class="expandable-body">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $publishable,
					'name'		=> 'publishDate',
					'label'		=> 'Publish Date',
					'disabled'	=> $publishable->isArchived(),
				), 'core') ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $publishable,
					'name'		=> 'archiveDate',
					'label'		=> 'Archive Date',
					'disabled'	=> $publishable->isArchived(),
				), 'core') ?>
			</div>
			<div class="expandable-toggle">
				Advanced
			</div>
		</div>
	</div>
</fieldset>