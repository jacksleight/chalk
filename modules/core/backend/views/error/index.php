<?php $this->outer('/layout/html', [
	'title'	=> 'Error',
]) ?>
<?php $this->block('body') ?>

<div class="flex-col">
    <div class="body flex">
        <h1>Error</h1>
        <p>Sorry, an error occured, please try again.</p>
        <?php if (isset($e) && $this->user->isDeveloper()) { ?>
            <pre class="exception"><?= $e ?></pre>
        <?php } ?>
    </div>
</div>