<?php $this->layout('/layout/body') ?>
<?php $this->block('main') ?>

<?= $content->main ?>

<?php $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?= $this->render('nav', ['items' => [
		[
			'label' => 'Users',
			'name'  => 'setting',
			'params'=> ['controller' => 'user'],
		], [
			'label' => 'Sites',
			'name'  => 'setting',
			'params'=> ['controller' => 'domain'],
		], [
			'label' => 'Structures',
			'name'  => 'setting',
			'params'=> ['controller' => 'setting_structure'],
		]
	]]) ?>
</nav>