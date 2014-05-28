<div class="content">

	<span class="content-select btn btn-focus">
		<i class="fa fa-image"></i>
		Select Image
	</span>
	<span class="content-name"></span>

	<?= $this->render('input', [
		'type'		=> 'hidden',
		'value'		=> $entity->{$name},
	]) ?>
</div>