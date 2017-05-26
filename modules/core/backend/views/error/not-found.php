<?php $this->outer('/layout/page_wide', [
    'title' => 'Not Found',
]) ?>
<?php $this->block('main') ?>

<div class="flex-col">
    <div class="body flex">
        <h1>Not Found</h1>
        <p>Sorry, the page you requested does not exist.</p>
    </div>
</div>