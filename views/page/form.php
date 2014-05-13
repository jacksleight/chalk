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
						'note'			=> 'Text used in navigation and URLs',
					)) ?>
				</div>
			</fieldset>
		<? } ?>
	</div>
	<div class="fix">
		<fieldset>
			<ul class="toolbar">
				<?= $this->render('/content/actions-primary') ?>
			</ul>
			<ul class="toolbar">
				<?= $this->render('/content/actions-secondary') ?>
				<? if (isset($node) && !$node->isRoot()) { ?>
					<li class="space"><a href="<?= $this->url([
						'action' => 'delete'
					]) ?>" class="btn btn-negative btn-quiet confirmable">
						<i class="fa fa-times"></i>
						Remove <?= $entityType->singular ?>
					</a>
					<small>&nbsp;from <?= $structure->label ?></small>
					</li>
				<? } ?>
			</ul>
		</fieldset>
	</div>
</form>