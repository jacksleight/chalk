<?= $this->render('/content/actions-top') ?>
<?= $this->render('/content/header') ?>
<?= $this->render('/content/meta') ?>
<form action="<?= $this->url->route() ?>" method="post">
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
	<fieldset>
		<?= $this->render('/content/actions-bottom') ?>
	</fieldset>
</form>