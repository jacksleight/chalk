<?php $this->outer('/layout/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col">
	<div class="header">
		<h1>
			<?php if (!$domain->isNew()) { ?>
				Site
				<? //$domain->name ?>
			<?php } else { ?>
				New <?= $info->singular ?>
			<?php } ?>
		</h1>
	</div>
	<div class="flex body">
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
				)) ?>
				<?php $this->start() ?>
					<?= $this->render('/element/form-item', array(
						'entity'	=> $domain,
						'name'		=> 'structures',
						'label'		=> 'Structures',
						'values'    => $this->em('Chalk\Core\Structure')->all(),
					)) ?>
				<?php $html = $this->end() ?>
				<?= $this->render('/element/expandable', [
					'content'		=> $html,
					'buttonLabel'	=> 'Advanced',
				], 'core') ?>				
			</div>
		</fieldset>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>HTML</h2>
				<p>This will be inserted at the end of the <code>&lt;head&gt;</code> and <code>&lt;body&gt;</code> elements of every page.</p>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'head',
					'class'		=> 'monospaced editor-code',
					'rows'		=> 10,
					'label'		=> 'Head',
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'body',
					'class'		=> 'monospaced editor-code',
					'rows'		=> 10,
					'label'		=> 'Body',
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok">
					Save <?= $info->singular ?>
				</button>
			</li>
		</ul>
	</fieldset>
</form>