<?= $this->render('/element/form-item', array(
	'type'		=> 'input_content',
	'entity'	=> $entity,
	'name'		=> 'content',
	'label'		=> 'Content',
    'filters'   => 'core_link',
	'disabled'	=> $entity->isArchived(),
), 'core') ?>