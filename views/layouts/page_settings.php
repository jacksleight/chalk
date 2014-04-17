<? $this->layout('/layouts/body') ?>
<? $this->block('main') ?>

<?= $content->main ?>

<? $this->block('sidebar') ?>

<nav class="menu" role="navigation">
	<?= $this->render('nav', ['items' => [
		[
			'label' => 'Users',
			'params'=> ['controller' => 'user'],
		], [
			'label' => 'Domains',
			'params'=> ['controller' => 'domain'],
		], [
			'label' => 'Menus',
			'params'=> ['controller' => 'menu'],
		]
	]]) ?>
</nav>