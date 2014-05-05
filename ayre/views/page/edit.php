<? $this->layout('/layouts/page') ?>
<? $this->block('main') ?>

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
				'type'		=> 'select',
				'entity'	=> $entity,
				'name'		=> 'layout',
				'label'		=> 'Layout',
				'null'		=> 'Default',
				'values'	=> $this->app->layouts(),
				'disabled'	=> $entity->isArchived(),
			)) ?>
		</div>
	</fieldset>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Content</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-input', array(
				'entity'	=> $entity,
				'name'		=> 'content',
				'type'		=> 'content',
				'disabled'	=> $entity->isArchived(),
			)) ?>
		</div>
	</fieldset>
	<fieldset>
		<?= $this->render('/content/actions-bottom') ?>
	</fieldset>
</form>