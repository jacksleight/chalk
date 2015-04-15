<?php $this->extend('/content/form', [], 'core') ?>
<?php $this->block('general-bottom') ?>

<?= $this->render('/element/form-item', array(
	'entity'		=> $content,
	'name'			=> 'url',
	'label'			=> 'URL',
	'placeholder'	=> 'http://example.com/',
), 'core') ?>