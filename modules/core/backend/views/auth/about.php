<?php
use Chalk\App as Chalk;
?>

<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/body_simple') ?>
	<?php $this->block('main') ?>
<?php } ?>

<div class="flex-col" data-modal-size="500x110">
	<div class="header">
		<ul class="toolbar toolbar-right">
			<li><span class="btn btn-out btn-lighter modal-close icon-cancel">
				Close
			</span></li>
		</ul>
		<h1>
			Chalk
			<small><?= Chalk::VERSION ?></small>
		</h1>
	</div>
	<div class="body">
		<div class="licence">
			<p>Copyright (c) 2014 Jack Sleight &ndash; <a target="_blank" href="http://jacksleight.com/">http://jacksleight.com/</a></p>
		</div>
	</div>
</div>