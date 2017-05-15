<?php
use Chalk\Chalk;
?>

<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<div class="flex-col" data-modal-size="500x110">
	<div class="flex">
		<h1>
			Chalk
			<small><?= Chalk::VERSION ?></small>
		</h1>
		<div class="licence">
			<p>Copyright (c) 2014 Jack Sleight &ndash; <a target="_blank" href="http://jacksleight.com/">http://jacksleight.com/</a></p>
		</div>
	</div>
</div>