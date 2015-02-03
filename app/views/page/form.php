<?php $this->extend('/content/form', [], 'Chalk\Core') ?>
<?php $this->block('general-bottom') ?>

<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'summary',
	'label'		=> 'Summary',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 7,
), 'Chalk\Core') ?>
<?= $this->content('general-bottom') ?>
<?php $this->start() ?>
	<?= $this->render('/element/form-item', array(
		'type'		=> 'select',
		'entity'	=> $content,
		'name'		=> 'layout',
		'label'		=> 'Layout',
		'null'		=> 'Default',
		'values'	=> $this->app->layouts(),
	), 'Chalk\Core') ?>
	<?= $this->content('general-advanced-bottom') ?>
<?php $html = $this->end() ?>
<?= $this->render('/element/expandable', [
	'content'		=> $html,
	'buttonLabel'	=> 'Advanced',
], 'Chalk\Core') ?>

<?php $this->block('general-after') ?>

<fieldset class="form-block">
	<div class="form-legend">
		<h2>Content</h2>
	</div>
	<div class="form-items">
		<?= $this->render('/element/form-item', array(
			'entity'	=> $content,
			'name'		=> 'blocks',
			'label'		=> 'Blocks',
			'type'		=> 'array_textarea',
			'class'		=> 'monospaced editor-content',
			'rows'		=> 20,
			'stackable'	=> false,
		), 'Chalk\Core') ?>
	</div>
</fieldset>
<?= $this->content('general-after') ?>