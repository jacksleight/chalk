<?php 
$browser['entity'] = \Chalk\Chalk::info(isset($browser['entity'])
	? $browser['entity']
	: 'Chalk\Core\Content');
?>
<div class="content" data-entity="<?= $browser['entity']->name ?>">
	<div class="content-controls">
		<span class="content-remove btn btn-quieter btn-icon icon-remove">Remove</span>
		<span class="content-select btn btn-quieter btn-icon icon-browse">Browse</span>
	</div>
	<div class="content-holder">
		<?php if (isset($entity->{$name})) { ?>
			<?= $this->render('/content/card', [
				'content' => $this->em('Chalk\Core\Content')->id((string) $entity->{$name})
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