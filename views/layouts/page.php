<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('nav') ?>

<ul>
	<li>
		<a href="<?= $this->url([
			'controller'	=> 'content',
			'entityType'	=> 'core-file',
		], 'entity', true) ?>">
			<i class="fa fa-picture-o fa-fw"></i>
			Files
		</a>
	</li>
	<li>
		<a href="<?= $this->url([
			'controller'	=> 'content',
			'entityType'	=> 'core-document',
		], 'entity', true) ?>">
			<i class="fa fa-file fa-fw"></i>
			Documents
		</a>
	</li>
</ul>