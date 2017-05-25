<?= $this->parent() ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'input_chalk',
	'entity'	=> $entity,
	'name'		=> 'link',
	'label'		=> 'Link',
    'filters'   => 'core_link',
), 'core') ?>