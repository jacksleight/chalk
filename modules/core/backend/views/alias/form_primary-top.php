<?= $this->parent() ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'input_chalk',
	'entity'	=> $entity,
	'name'		=> 'entity',
	'label'		=> 'Content',
    'filters'   => 'core_info_link',
), 'core') ?>