<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<ul class="toolbar">
			<?= $this->render('/content/tools', [], 'core') ?>
		</ul>
		<?= $this->render('/content/header', [], 'core') ?>
		<?= $this->render('/content/meta', [], 'core') ?>
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
				), 'core') ?>
			</div>
		</fieldset>
		<?= $this->render('/behaviour/publishable/form', ['publishable' => $content], 'core') ?>
		<?= $this->render('/content/node', [], 'core') ?>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<?= $this->render('/content/actions-primary', [], 'core') ?>
		</ul>
		<ul class="toolbar">
			<?= $this->render('/content/actions-secondary', [], 'core') ?>
		</ul>
	</fieldset>
</form>