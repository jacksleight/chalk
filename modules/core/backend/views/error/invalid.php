<?php $this->outer('/layout/html', [
	'title'	=> 'Forbidden',
]) ?>
<?php $this->block('body') ?>

<?php
$info = isset($info)
    ? $info
    : Chalk\Chalk::info($entity);
?>
<div class="flex-col">
    <div class="body flex">
        <h1>Nothing To Display</h1>
        <p>This <?= strtolower($info->singular) ?> does not have a page on the site.</p>
    </div>
</div>