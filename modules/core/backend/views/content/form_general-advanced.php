<?= $this->render('/element/form-item', array(
	'entity'	=> $content,
	'name'		=> 'tagsText',
	'label'		=> 'Tags',
	'disabled'	=> $content->isProtected(),
), 'core') ?>