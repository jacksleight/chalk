<?= $this->parent() ?>
<fieldset class="form-block">
	<div class="form-legend">
		<h2>Tracking Code</h2>
	</div>
	<div class="form-items">
		<?= $this->render('/element/form-item', array(
			'entity'	=> $entity,
			'name'		=> 'head',
			'class'		=> 'monospaced editor-code',
			'rows'		=> 10,
			'label'		=> 'Head',
			'note'		=> 'Added to the <code>&lt;head&gt;</code> element of every page',
		)) ?>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $entity,
			'name'		=> 'body',
			'class'		=> 'monospaced editor-code',
			'rows'		=> 10,
			'label'		=> 'Body',
			'note'		=> 'Added to the <code>&lt;body&gt;</code> element of every page',
		)) ?>
	</div>
</fieldset>