<?php 
$browser['entity'] = \Ayre::entity(isset($browser['entity'])
	? $browser['entity']
	: 'core_content');
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
		<? if (isset($entity->{$name})) { ?>
			<?= $this->render('/content/card', [
				'content' => $this->em('core_content')->fetchByMasterId((string) $entity->{$name})
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