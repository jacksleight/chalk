<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<ul class="toolbar">
			<?= $this->child('/content/tools', [], 'Chalk\Core') ?>
		</ul>
		<?= $this->child('/content/header', [], 'Chalk\Core') ?>
		<?= $this->child('/content/meta', [], 'Chalk\Core') ?>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $content,
					'name'		=> 'name',
					'label'		=> 'Title',
					'autofocus'	=> true,
				), 'Chalk\Core') ?>
				<?= $this->render('/element/form-item', array(
					'type'		=> 'textarea',
					'entity'	=> $content,
					'name'		=> 'summary',
					'label'		=> 'Summary',
					'class'		=> 'monospaced editor-content',
					'rows'		=> 7,
				), 'Chalk\Core') ?>
				<div class="expandable">
					<div class="expandable-body">
						<?= $this->render('/element/form-item', array(
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
				<?= $this->render('/element/form-item', array(
					'entity'	=> $content,
					'name'		=> 'blocks',
					'label'		=> 'Blocks',
					'type'		=> 'array_textarea',
					'class'		=> 'monospaced editor-content',
					'rows'		=> 20,
					'stackable'	=> false,
				), 'Chalk\Core') ?>
			</div>
		</fieldset>
		<?= $this->child('/behaviour/publishable/form', ['publishable' => $content], 'Chalk\Core') ?>
		<?= $this->child('/content/node', [], 'Chalk\Core') ?>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<?= $this->child('/content/actions-primary', [], 'Chalk\Core') ?>
		</ul>
		<ul class="toolbar">
			<?= $this->child('/content/actions-secondary', [], 'Chalk\Core') ?>
			<?= $this->child('/content/actions-node', [], 'Chalk\Core') ?>
		</ul>
	</fieldset>
</form>