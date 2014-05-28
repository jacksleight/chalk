<div class="content">
	<ul class="toolbar">
		<li><span class="content-remove btn btn-negative btn-quiet">
			<i class="fa fa-times"></i>
			Remove Content
		</span></li>
		<li><span class="content-select btn btn-focus btn-quiet">
			<i class="fa fa-folder"></i>
			Browse Content
		</span></li>
	</ul>
	<div class="content-holder">
		<? if (isset($entity->{$name})) { ?>
			<?= $this->render('/content/card', [
				'content' => $this->em('Ayre\Core\Content')->fetchByMasterId($entity->{$name}->id)
			]) ?>		
		<? } else { ?>
			<span class="placeholder">Nothing Selected</span>
		<? } ?>
	</div>
	<?= $this->render('input', [
		'type'		=> 'hidden',
		'value'		=> $entity->{$name},
	]) ?>
</div>