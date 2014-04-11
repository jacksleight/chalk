<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('nav') ?>

<ul>
	<li>
		<a href="<?= $this->url([
			'controller' => 'users',
		], 'entity', true) ?>">
			<i class="fa fa-user fa-fw"></i>
			Users
		</a>
	</li>
	<li>
		<a href="<?= $this->url([
			'controller' => 'domains',
		], 'entity', true) ?>">
			<i class="fa fa-globe fa-fw"></i>
			Domains
		</a>
	</li>
	<li>
		<a href="<?= $this->url([
			'controller' => 'menus',
		], 'entity', true) ?>">
			<i class="fa fa-bars fa-fw"></i>
			Menus
		</a>
	</li>
</ul>