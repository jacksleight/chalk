<?php 
if (!isset($restricts)) {
	$restricts = \Chalk\Chalk::info($md['entity'])->name;
}
?>
<div class="input-content" data-params="<?= $this->escape(http_build_query(['restricts' => $restricts])) ?>">
	<div class="input-content-controls">
		<span class="input-content-remove btn btn-lightest btn-out btn-icon icon-remove"><span>Remove</span></span>
		<span class="input-content-select btn btn-lightest btn-icon icon-browse"><span>Browse</span></span>
	</div>
	<div class="input-content-holder">
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