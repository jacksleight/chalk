<?php $this->outer('/layout/page_settings') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col">
	<div class="header">
		<h1>
			<?php if (!$domain->isNew()) { ?>
				Site
				<?php //$domain->name ?>
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
					'name'		=> 'label',
					'label'		=> 'Title',
					'autofocus'	=> true,
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'name',
					'label'		=> 'Domain',
					'autofocus'	=> true,
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'emailAddress',
					'label'		=> 'Email Address',
					'autofocus'	=> true,
				)) ?>
				<?php if ($req->user->isDeveloper()) { ?>
					<?= $this->render('/element/form-item', array(
						'entity'	=> $domain,
						'name'		=> 'structures',
						'label'		=> 'Structures',
						'values'    => $this->em('Chalk\Core\Structure')->all(),
					)) ?>
				<?php } ?>
			</div>
		</fieldset>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>Embed Codes</h2>
				<p>These will be inserted at the end of the <code>&lt;head&gt;</code> and <code>&lt;body&gt;</code> elements of every page.</p>
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