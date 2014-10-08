<?php 
$browser['entity'] = \Chalk\Chalk::entity(isset($browser['entity'])
	? $browser['entity']
	: 'Chalk\Core\Content');
?>
<div class="content" data-entity="<?= $browser['entity']->name ?>">
	<ul class="toolbar">
		<li><span class="content-remove btn btn-quieter btn-icon">
			<i class="fa fa-times"></i>
		</span></li>
		<li><span class="content-select btn btn-quieter btn-icon">
			<i class="fa fa-folder-o"></i>
		</span></li>
	</ul>
	<div class="content-holder">
		<?php if (isset($entity->{$name})) { ?>
			<?= $this->render('/content/card', [
				'content' => $this->em('Chalk\Core\Content')->fetchByMasterId((string) $entity->{$name})
			]) ?>		
		<?php } else { ?>
			<span class="placeholder">Nothing Selected</span>
		<?php } ?>
	</div>
	<?= $this->render('input', [
		'type'		=> 'hidden',
		'value'		=> $entity->{$name},
	]) ?>
</div>