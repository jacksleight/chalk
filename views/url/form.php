<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<ul class="toolbar">
			<?= $this->render('/content/tools') ?>
		</ul>
		<?= $this->render('/content/header') ?>
		<?= $this->render('/content/meta') ?>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $content,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
					'disabled'	=> $content->isArchived(),
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'entity'		=> $content,
					'name'			=> 'url',
					'label'			=> 'URL',
					'placeholder'	=> 'http://example.com/',
					'disabled'		=> $content->isArchived(),
				)) ?>
			</div>
		</fieldset>
		<?= $this->render('/content/node') ?>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<?= $this->render('/content/actions-primary') ?>
		</ul>
		<ul class="toolbar">
			<?= $this->render('/content/actions-secondary') ?>
			<?= $this->render('/content/actions-node') ?>
		</ul>
	</fieldset>
</form>