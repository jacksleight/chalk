<?= $this->render('/element/form-item', array(
	'type'		=> 'input_content',
	'entity'	=> $content,
	'name'		=> 'content',
	'label'		=> 'Content',
	'filters'	=> 'url',
	'disabled'	=> $content->isArchived(),
), 'core') ?>