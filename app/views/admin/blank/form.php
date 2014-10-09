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
				<?= $this->render('/element/form-item', array(
					'entity'	=> $content,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
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
		</ul>
	</fieldset>
</form>