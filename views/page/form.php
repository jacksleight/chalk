<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<ul class="toolbar">
			<?= $this->render('/content/tools') ?>
		</ul>
		<?= $this->render('/content/header') ?>
		<?= $this->render('/content/meta') ?>
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
				)) ?>
				<?= $this->render('/elements/form-item', array(
					'type'		=> 'textarea',
					'entity'	=> $content,
					'name'		=> 'summary',
					'label'		=> 'Summary',
					'class'		=> 'monospaced html',
					'rows'		=> 5,
					'disabled'	=> $content->isArchived(),
				)) ?>
				<div class="expandable">
					<div class="expandable-body">
						<?= $this->render('/elements/form-item', array(
							'type'		=> 'array_pairs',
							'entity'	=> $content,
							'name'		=> 'metas',
							'label'		=> 'Metadata',
							'disabled'	=> $content->isArchived(),
							'datalist'	=> [
							    'application-name',
							    'author',
							    'bingbot',
							    'copyright',
							    'description',
							    'fb:admins',
							    'generator',
							    'google-site-verification',
							    'googlebot',
							    'keywords',
							    'language',
							    'msvalidate.01',
							    'og:description',
							    'og:image',
							    'og:title',
							    'og:type',
							    'p:domain_verify',
							    'robots',
							    'twitter:card',
							    'twitter:description',
							    'twitter:image',
							    'twitter:title',
							    'twitter:url',
							],
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
					'type'		=> 'array_textareas',
					'class'		=> 'monospaced html',
					'rows'		=> 15,
					'disabled'	=> $content->isArchived(),
				)) ?>
			</div>
		</fieldset>
		<?= $this->render('/content/node') ?>
	</div>
	<fieldset class="fix">
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
</form>