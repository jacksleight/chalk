<?php if (!$req->isAjax()) { ?>
	<?php $this->parent('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>?post=1" method="post" class="fill" data-modal-size="fullscreen">
	<div class="fix fix-header">
		<ul class="toolbar">
			<li><span class="btn btn-quieter modal-close icon-cancel">
				Close
			</span></li>
		</ul>
		<h1>
			Source
		</h1>
	</div>
	<div class="flex flex-full">
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
	<fieldset class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn btn-positive icon-ok">
					Update Source
				</button>
			</li>
		</ul>
	</fieldset>
</form>