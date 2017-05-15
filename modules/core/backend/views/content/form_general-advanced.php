<?= $this->render('/element/form-item', array(
    'type'      => 'input_tag',
	'entity'	=> $entity,
	'name'		=> 'tagsText',
	'label'		=> 'Tags',
    'values'    => $this->em('core_tag')->all(),
	'disabled'	=> $entity->isProtected(),
), 'core') ?>