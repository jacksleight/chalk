<?php $this->outer('/layout/page', [
	'title'	=> 'Error',
]) ?>
<?php $this->block('main') ?>

<div class="flex-col">
    <div class="body flex">
        <h1>Error</h1>
        <p>Sorry, an error occured, please try again.</p>
        <?php if (isset($e) && $req->user->isDeveloper()) { ?>
            <pre class="exception"><?= $e ?></pre>
        <?php } ?>
    </div>
</div>