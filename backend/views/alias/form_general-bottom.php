<?= $this->render('/element/form-item', array(
	'type'		=> 'input_content',
	'entity'	=> $content,
	'name'		=> 'content',
	'label'		=> 'Content',
    'filters'   => 'core_link',
	'disabled'	=> $content->isArchived(),
), 'core') ?>