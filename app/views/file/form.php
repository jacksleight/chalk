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
					'label'		=> 'Name',
				), 'Chalk\Core') ?>
			</div>
		</fieldset>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>Download</h2>
			</div>
			<div class="form-items">
				<div class="form-item">
					<a href="<?= $this->root->url($content->file) ?>" class="block" target="_blank">
						<?= $this->child('/content/card', ['content' => $content->getObject()]) ?>
					</a>
				</div>
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