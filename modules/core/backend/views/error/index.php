<?php $this->outer('/layout/page_wide', [
	'title'	=> 'Error',
]) ?>
<?php $this->block('main') ?>

<div class="flex-col flex-center">
    <div class="body">
        <h1>Error</h1>
        <p>Sorry, an error occured, please try again.</p>
        <?php if (isset($e) && $this->user->isDeveloper()) { ?>
            <pre class="exception"><?= $e ?></pre>
        <?php } ?>
    </div>
</div>