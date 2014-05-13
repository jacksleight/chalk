<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="fix">
		<ul class="toolbar">
			<?= $this->render('/content/tools') ?>
		</ul>
		<?= $this->render('/content/header') ?>
		<?= $this->render('/content/meta') ?>
	</div>
	<div class="flex">
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>Details</h2>
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
	</div>
	<div class="fix">
		<fieldset>
			<ul class="toolbar">
				<?= $this->render('/content/actions-primary') ?>
			</ul>
			<ul class="toolbar">
				<?= $this->render('/content/actions-secondary') ?>
			</ul>
		</fieldset>
	</div>
</form>