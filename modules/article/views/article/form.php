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
					'label'		=> 'Title',
					'autofocus'	=> true,
					'disabled'	=> $content->isArchived(),
				), 'core') ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $content,
					'name'		=> 'activeDate',
					'label'		=> 'Active Date',
					'disabled'	=> $content->isArchived(),
				), 'core') ?>
				<?= $this->render('/elements/form-item', array(
					'type'		=> 'textarea',
					'entity'	=> $content,
					'name'		=> 'summary',
					'label'		=> 'Summary',
					'class'		=> 'monospaced html',
					'rows'		=> 5,
					'disabled'	=> $content->isArchived(),
				), 'core') ?>
				<div class="expandable">
					<div class="expandable-body">
						<?= $this->render('/elements/form-item', array(
							'type'		=> 'array',
							'entity'	=> $content,
							'name'		=> 'metas',
							'label'		=> 'Metadata',
							'disabled'	=> $content->isArchived(),
						), 'core') ?>
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
					'name'		=> 'contents',
					'type'		=> 'textarea_multiple',
					'class'		=> 'monospaced html',
					'rows'		=> 15,
					'disabled'	=> $content->isArchived(),
				), 'core') ?>
			</div>
		</fieldset>
		<?= $this->render('/content/node', [], 'core') ?>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<?= $this->render('/content/actions-primary', [], 'core') ?>
		</ul>
		<ul class="toolbar">
			<?= $this->render('/content/actions-secondary', [], 'core') ?>
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
</form>