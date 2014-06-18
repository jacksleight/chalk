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
					'type'	    => 'input_radio',
					'entity'	=> $content,
					'name'		=> 'status',
					'label'		=> 'Status',
					'disabled'	=> $content->isArchived(),
				), 'core') ?>
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $content,
					'name'		=> 'name',
					'label'		=> 'Title',
					'autofocus'	=> true,
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
							'entity'	=> $content,
							'name'		=> 'publishDate',
							'label'		=> 'Publish Date',
							'disabled'	=> $content->isArchived(),
						), 'core') ?>
						<?= $this->render('/elements/form-item', array(
							'entity'	=> $content,
							'name'		=> 'archiveDate',
							'label'		=> 'Archive Date',
							'disabled'	=> $content->isArchived(),
						), 'core') ?>
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
					'type'		=> 'array_textareas',
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
			<?= $this->render('/content/actions-node', [], 'core') ?>
		</ul>
	</fieldset>
</form>