<?php $this->parent('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<nav class="nav" role="navigation">
	<?= $this->child('nav', ['items' => $navigation->items('Chalk\Core\Content')]) ?>
</nav>