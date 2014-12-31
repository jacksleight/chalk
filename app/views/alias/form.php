<?php $this->extend('/content/form', [], 'Chalk\Core') ?>
<?php $this->block('general-bottom') ?>

<?= $this->render('/element/form-item', array(
	'type'		=> 'content',
	'entity'	=> $content,
	'name'		=> 'content',
	'label'		=> 'Content',
	'disabled'	=> $content->isArchived(),
), 'Chalk\Core') ?>