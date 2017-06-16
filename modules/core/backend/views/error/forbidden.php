<?php $this->outer('/layout/page_wide', [
	'title'	=> 'Forbidden',
]) ?>
<?php $this->block('main') ?>

<div class="flex-col flex-center">
    <div class="body">
        <h1>Forbidden</h1>
        <p>Sorry, you do not have permission to access this page.</p>
    </div>
</div>