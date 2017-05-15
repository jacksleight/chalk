<?php 
use Chalk\Chalk;
if (!isset($filters)) {
	$filters = [
		Chalk::info($md['entity'])->name => true,
	];
}
$value = isset($value)
	? $value
	: null;
?>
<div class="input-content" data-params="<?= $this->escape(http_build_query(['filters' => $filters])) ?>">
	<div class="input-content-controls">
		<span class="input-content-remove btn btn-lighter btn-out btn-icon icon-remove"><span>Remove</span></span>
		<span class="input-content-select btn btn-lighter btn-icon icon-browse"><span>Browse</span></span>
	</div>
	<div class="input-content-holder">
		<?php
		$content = isset($value)
			? $this->em('Chalk\Core\Content')->id($value)
			: null;
		?>
		<?php if ($content) { ?>
			<?= $this->inner('/content/card', [
				'content' => $content
			]) ?>		
		<?php } else if (isset($placeholder)) { ?>
			<span class="placeholder"><?= $placeholder ?></span>
		<?php } else { ?>
			<span class="placeholder">Nothing Selected</span>
		<?php } ?>
	</div>
	<?= $this->inner('input', [
		'type'		=> 'hidden',
		'value'		=> $value,
	]) ?>
</div>