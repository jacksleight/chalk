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
				'entity'	=> $entity,
				'name'		=> 'name',
				'label'		=> 'Name',
				'autofocus'	=> true,
				'disabled'	=> $entity->isArchived(),
			)) ?>
			<?= $this->render('/elements/form-item', array(
				'entity'		=> $entity,
				'name'			=> 'url',
				'label'			=> 'URL',
				'placeholder'	=> 'http://example.com/',
				'disabled'		=> $entity->isArchived(),
			)) ?>
		</div>
	</fieldset>
	<fieldset>
		<?= $this->render('/content/actions-bottom') ?>
	</fieldset>
</form>