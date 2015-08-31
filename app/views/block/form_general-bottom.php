<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'body',
	'label'		=> 'Content',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 20,
), 'core') ?>