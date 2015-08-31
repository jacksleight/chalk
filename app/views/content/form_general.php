<fieldset class="form-block">
	<div class="form-legend">
		<h2>General</h2>
	</div>
	<div class="form-items">
		<?= $this->partial('general-top') ?>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $content,
			'name'		=> 'name',
			'label'		=> 'Name',
			'autofocus'	=> true,
			'disabled'	=> $content->isProtected(),
		), 'core') ?>
		<?= $this->partial('general-bottom') ?>
	</div>
</fieldset>
<?= $this->partial('general-after') ?>