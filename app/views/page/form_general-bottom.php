<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'summary',
	'label'		=> 'Summary',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 7,
), 'core') ?>
<?= $this->content('general-bottom') ?>
<?php $this->start() ?>
	<?= $this->render('/element/form-item', array(
		'type'		=> 'select',
		'entity'	=> $content,
		'name'		=> 'layout',
		'label'		=> 'Layout',
		'null'		=> 'Default',
		'values'	=> $this->app->layouts(),
	), 'core') ?>
	<?= $this->content('general-advanced-bottom') ?>
<?php $html = $this->end() ?>
<?= $this->render('/element/expandable', [
	'content'		=> $html,
	'buttonLabel'	=> 'Advanced',
], 'core') ?>