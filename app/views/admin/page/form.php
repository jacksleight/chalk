<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<ul class="toolbar">
			<?= $this->render('/content/tools', [], 'Chalk\Core') ?>
		</ul>
		<?= $this->render('/content/header', [], 'Chalk\Core') ?>
		<?= $this->render('/content/meta', [], 'Chalk\Core') ?>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $content,
					'name'		=> 'name',
					'label'		=> 'Title',
					'autofocus'	=> true,
				), 'Chalk\Core') ?>
				<?= $this->render('/elements/form-item', array(
					'type'		=> 'textarea',
					'entity'	=> $content,
					'name'		=> 'summary',
					'label'		=> 'Summary',
					'class'		=> 'monospaced html',
					'rows'		=> 5,
				), 'Chalk\Core') ?>
				<div class="expandable">
					<div class="expandable-body">
						<?= $this->render('/elements/form-item', array(
							'type'		=> 'select',
							'entity'	=> $content,
							'name'		=> 'layout',
							'label'		=> 'Layout',
							'null'		=> 'Default',
							'values'	=> $this->app->layouts(),
						), 'Chalk\Core') ?>
					</div>
					<div class="expandable-toggle">
						Advanced
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>Content</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-input', array(
					'entity'	=> $content,
					'name'		=> 'blocks',
					'label'		=> 'Blocks',
					'type'		=> 'array_textarea',
					'class'		=> 'monospaced html',
					'rows'		=> 15,
					'stackable'	=> false,
				), 'Chalk\Core') ?>
			</div>
		</fieldset>
		<?= $this->render('/behaviour/publishable/form', ['publishable' => $content], 'Chalk\Core') ?>
		<?= $this->render('/content/node', [], 'Chalk\Core') ?>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<?= $this->render('/content/actions-primary', [], 'Chalk\Core') ?>
		</ul>
		<ul class="toolbar">
			<?= $this->render('/content/actions-secondary', [], 'Chalk\Core') ?>
			<?= $this->render('/content/actions-node', [], 'Chalk\Core') ?>
		</ul>
	</fieldset>
</form>