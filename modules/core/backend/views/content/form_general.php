<fieldset class="form-block">
	<div class="form-legend">
		<h2>General</h2>
	</div>
	<div class="form-items">
		<?= $this->partial('general-top') ?>
		<?php if (!$content->isProtected()) { ?>
			<?= $this->render('/element/form-item', array(
				'entity'	=> $content,
				'name'		=> 'name',
				'label'		=> 'Name',
				'autofocus'	=> true,
			), 'core') ?>
		<?php } ?>
		<?= $this->partial('general-bottom') ?>
		<?= $this->render('/element/expandable', [
			'content'		=> $this->partial('general-advanced'),
			'buttonLabel'	=> 'Advanced',
		], 'core') ?>		
	</div>
</fieldset>
<?= $this->partial('general-after') ?>