<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/html') ?>
	<?php $this->block('body') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>" class="flex-col" novalidate>
	<div class="header">
		<h1>Browse</h1>
	</div>
	<?= $this->render("/content/browser", [
		'index'		=> $index,
		'filters'	=> $req->filters,
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