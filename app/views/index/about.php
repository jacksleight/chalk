<?php if (!$req->isAjax()) { ?>
	<?php $this->parent('/layout/page_content') ?>
	<?php $this->block('main') ?>
<?php } ?>

<div class="fill" data-modal-size="500x110">
	<div class="flex">
		<ul class="toolbar">
			<li><span class="btn modal-close icon-cancel">
				Close
			</span></li>
		</ul>
		<h1>
			Chalk
			<small><?= \Chalk\Chalk::VERSION ?></small>
		</h1>
		<div class="licence">
			<p>Copyright (c) 2014 Jack Sleight &ndash; <a target="_blank" href="http://jacksleight.com/">http://jacksleight.com/</a></p>
		</div>
	</div>
</div>