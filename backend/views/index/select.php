<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/html') ?>
	<?php $this->block('body') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>" class="flex-col" data-modal-size="fullscreen">
	<div class="header">
		<ul class="toolbar toolbar-right">
	        <li class="toolbar-gap"><span class="btn btn-out btn-lighter modal-close icon-cancel">
	            Close
	        </span></li>
		</ul>
		<h1>Browse</h1>
	</div>
	<?= $this->render("/content/browser", [
		'index'	=> $index,
	]) ?>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok" formmethod="post">
					Select Content
				</button>
			</li>
		</ul>
	</div>
<form>