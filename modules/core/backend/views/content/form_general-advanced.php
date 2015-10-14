<?= $this->render('/element/form-item', array(
    'type'      => 'input_tag',
	'entity'	=> $content,
	'name'		=> 'tagsText',
	'label'		=> 'Tags',
    'values'    => $this->em('core_tag')->all(),
	'disabled'	=> $content->isProtected(),
), 'core') ?>