<?= $this->parent() ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $entity,
	'name'		=> 'body',
	'label'		=> 'Content',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 20,
), 'core') ?>