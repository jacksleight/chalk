<?php $this->outer('/layout/page') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>?post=1" method="post" class="flex-col">
	<div class="header">
		<h1>
			Source
		</h1>
	</div>
	<div class="flex body-flush">
		<?= $this->render('/element/form-input', array(
			'entity'	=> $source,
			'type'		=> 'input_hidden',
			'name'		=> 'lang',
		)) ?>
		<?= $this->render('/element/form-input', array(
			'entity'	=> $source,
			'type'		=> 'textarea',
			'name'		=> 'code',
			'class'		=> 'editor-code editor-code-' . (isset($source->lang) ? $source->lang : null),
			'autofocus'	=> true,
			'rows'		=> 20,
		)) ?>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok">
					Update Source
				</button>
			</li>
		</ul>
	</fieldset>
</form>