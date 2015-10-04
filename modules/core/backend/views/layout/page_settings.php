<?php $this->outer('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<nav class="nav" role="navigation">
	<?= $this->inner('nav', ['items' => $this->navList->children('core_setting')]) ?>
</nav>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>