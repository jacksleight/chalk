<?= $this->render('/content/actions-top') ?>
<?= $this->render('/content/header') ?>
<?= $this->render('/content/meta') ?>
<form action="<?= $this->url->route() ?>" method="post">
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
				'type'		=> 'select',
				'entity'	=> $content,
				'name'		=> 'layout',
				'label'		=> 'Layout',
				'null'		=> 'Default',
				'values'	=> $this->app->layouts(),
				'disabled'	=> $content->isArchived(),
			)) ?>
		</div>
	</fieldset>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Content</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/elements/form-input', array(
				'entity'	=> $content,
				'name'		=> 'content',
				'type'		=> 'html_multiple',
				'disabled'	=> $content->isArchived(),
			)) ?>
		</div>
	</fieldset>
	<? if (isset($node)) { ?>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2><?= $node->structure->name ?></h2>
			</div>
			<div class="form-items">	
				<?= $this->render('/elements/form-item', array(
					'entity'		=> $node,
					'name'			=> 'name',
					'label'			=> 'Label',
					'placeholder'	=> $content->name,
					'note'			=> 'Alternative text used in navigation and URLs',
				)) ?>
			</div>
		</fieldset>
	<? } ?>
	<fieldset>
		<?= $this->render('/content/actions-bottom') ?>
	</fieldset>
</form>