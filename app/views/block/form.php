<?php $this->extend('/content/form', [], 'Chalk\Core') ?>
<?php $this->block('general-bottom') ?>

<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'body',
	'label'		=> 'Body',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 20,
), 'Chalk\Core') ?>