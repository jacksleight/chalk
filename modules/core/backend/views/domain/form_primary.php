<?= $this->parent() ?>
<fieldset class="form-block">
	<div class="form-legend">
		<h2>Embed Codes</h2>
		<p>These will be inserted at the end of the <code>&lt;head&gt;</code> and <code>&lt;body&gt;</code> elements of every page.</p>
	</div>
	<div class="form-items">
		<?= $this->render('/element/form-item', array(
			'entity'	=> $entity,
			'name'		=> 'head',
			'class'		=> 'monospaced editor-code',
			'rows'		=> 10,
			'label'		=> 'Head',
		)) ?>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $entity,
			'name'		=> 'body',
			'class'		=> 'monospaced editor-code',
			'rows'		=> 10,
			'label'		=> 'Body',
		)) ?>
	</div>
</fieldset>