<?= $this->render('/element/form-item', [
	'entity'	=> $entity,
	'name'		=> 'isProtected',
	'label'		=> 'Protected',
], 'core') ?>
<?= $this->render('/element/form-item', [
	'type'		=> 'textarea',
    'rows'      => 10,
	'entity'	=> $entity,
	'name'		=> 'dataJson',
	'label'		=> 'Data',
    'class'     => 'monospaced editor-code editor-code-json',
], 'core') ?>