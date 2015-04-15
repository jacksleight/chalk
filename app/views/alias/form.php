<?php $this->extend('/content/form', [], 'core') ?>
<?php $this->block('general-bottom') ?>

<?= $this->render('/element/form-item', array(
	'type'		=> 'input_content',
	'entity'	=> $content,
	'name'		=> 'content',
	'label'		=> 'Content',
	'restricts'	=> 'url',
	'disabled'	=> $content->isArchived(),
), 'core') ?>