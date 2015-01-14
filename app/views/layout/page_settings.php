<?php $this->parent('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<nav class="menu menu-simple" role="navigation">
	<?= $this->child('nav', ['items' => $this->app->fire('Chalk\Core\Event\ListSettings')->settings()]) ?>
</nav>