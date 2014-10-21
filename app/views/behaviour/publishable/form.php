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
		<div class="expandable">
			<div class="expandable-body">
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
			</div>
			<div class="expandable-toggle">
				Advanced
			</div>
		</div>
	</div>
</fieldset>