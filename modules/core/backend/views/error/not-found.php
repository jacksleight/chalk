<?php $this->outer('/layout/body_error', [
    'title' => 'Not Found',
]) ?>
<?php $this->block('main') ?>

<div class="flex-col flex-center">
    <div class="body">
        <h1>Not Found</h1>
        <p>Sorry, the page you requested does not exist.</p>
    </div>
</div>