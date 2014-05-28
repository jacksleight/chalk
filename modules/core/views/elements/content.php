<div class="content">
	<ul class="toolbar">
		<li><span class="content-select btn btn-focus">
			<i class="fa fa-folder"></i>
			Browse Content
		</span></li>
	</ul>
	<div class="content-holder">
		<?= $this->render('/content/card', ['content' => $entity->{$name}]) ?>		
	</div>
	<?= $this->render('input', [
		'type'		=> 'hidden',
		'value'		=> $entity->{$name},
	]) ?>
</div>