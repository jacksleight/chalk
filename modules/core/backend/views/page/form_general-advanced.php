<?php if (!isset($content->data['delegate'])) { ?>
	<?= $this->render('/element/form-item', array(
		'type'		=> 'select',
		'entity'	=> $content,
		'name'		=> 'layout',
		'label'		=> 'Layout',
		'null'		=> 'Default',
		'values'	=> $this->app->layouts(),
	), 'core') ?>
<?php } ?>
<?= $this->parent() ?>