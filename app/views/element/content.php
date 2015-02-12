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
		<?php if (isset($value)) { ?>
			<?php
			$content = $this->em('Chalk\Core\Content')->id($value);
			?>
			<?php if ($content) { ?>
				<?= $this->child('/content/card', [
					'content' => $content
				]) ?>		
			<?php } else { ?>
				<span class="placeholder">Nothing Selected</span>
			<?php } ?>
		<?php } else { ?>
			<span class="placeholder">Nothing Selected</span>
		<?php } ?>
	</div>
	<?= $this->child('input', [
		'type'		=> 'hidden',
		'value'		=> $value,
	]) ?>
</div>