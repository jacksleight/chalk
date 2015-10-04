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
			'stackable'	=> $req->user->isDeveloper(),
		), 'core') ?>
	</div>
</fieldset>
<?= $this->content('general-after') ?>