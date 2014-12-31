<?php $this->extend('/content/form', [], 'Chalk\Core') ?>
<?php $this->block('general-bottom') ?>

<?= $this->render('/element/form-item', array(
	'entity'		=> $content,
	'name'			=> 'url',
	'label'			=> 'URL',
	'placeholder'	=> 'http://example.com/',
), 'Chalk\Core') ?>