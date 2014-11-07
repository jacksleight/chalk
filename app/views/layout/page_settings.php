<?php $this->layout('/layout/body') ?>
<?php $this->block('main') ?>

<?= $content->main ?>

<?php $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?= $this->render('nav', ['items' => [
		[
			'label' => 'Users',
			'params'=> ['controller' => 'user'],
		], [
			'label' => 'Sites',
			'params'=> ['controller' => 'domain'],
		], [
			'label' => 'Structures',
			'params'=> ['controller' => 'setting_structure'],
		]
	]]) ?>
</nav>