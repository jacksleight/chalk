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
					'autofocus'	=> true,
				), 'Chalk\Core') ?>
				<?= $this->render('/element/form-item', array(
					'entity'		=> $content,
					'name'			=> 'url',
					'label'			=> 'URL',
					'placeholder'	=> 'http://example.com/',
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