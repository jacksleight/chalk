<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'summary',
	'label'		=> 'Summary',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 7,
), 'core') ?>
<?php if (!isset($content->data['delegate'])) { ?>
    <?= $this->render('/element/form-item', array(
        'type'      => 'select',
        'entity'    => $content,
        'name'      => 'layout',
        'label'     => 'Layout',
        'null'      => 'Default',
        'values'    => $this->app->layouts(),
    ), 'core') ?>
<?php } ?>