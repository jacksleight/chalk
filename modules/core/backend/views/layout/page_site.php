<?php $this->outer('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<div class="body">
    <nav class="nav" role="navigation">
        <?= $this->inner('nav', ['items' => $this->navList->children('core_site')]) ?>
    </nav>
</div>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>