<?php $this->parent('/layout/body') ?>
<?php $this->block('main') ?>

<?= $this->content('main') ?>

<?php $this->block('sidebar') ?>

<?php $this->block('foot') ?>

<?= $this->content('foot') ?>