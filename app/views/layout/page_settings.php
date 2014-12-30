<?php $this->parent('/layout/body') ?>
<?php $this->block('main') ?>

<?= $content->main ?>

<?php $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?= $this->child('nav', ['items' => [
		[
			'label' => 'Users',
			'name'  => 'setting',
			'params'=> ['controller' => 'user'],
		], [
			'label' => 'Domains',
			'name'  => 'setting',
			'params'=> ['controller' => 'domain'],
		], [
			'label' => 'Structures',
			'name'  => 'setting',
			'params'=> ['controller' => 'setting_structure'],
		]
	]]) ?>
</nav>