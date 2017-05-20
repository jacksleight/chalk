<?php $this->outer('/layout/html', [
	'title'	=> 'Forbidden',
]) ?>
<?php $this->block('body') ?>

<div class="flex-col">
    <div class="body flex">
        <h1>Forbidden</h1>
        <p>Sorry, you do not have permission to access this page.</p>
    </div>
</div>