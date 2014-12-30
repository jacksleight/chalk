<?php
$items = [];
?>
<?php foreach ($value as $i => $item) { ?>
	<?php $this->start() ?>
		<div class="form-group form-group-vertical">
			<?php if ($stackable) { ?>
				<input
	                type="text"
	                name="<?= "{$name}[{$i}][name]" ?>"
	                id="<?= "{$id}[{$i}][name]" ?>"
	                placeholder="Name"
	                value="<?= $this->escape($item['name']) ?>"
	                <?= isset($datalist) ? "list=\"{$id}_datalist\"" : null ?>
	                <?= isset($disabled) && $disabled ? "disabled" : null ?>
	                <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
			<?php } else { ?>
				<span class="value disabled">
					<?= ucwords(\Coast\str_camel_split($item['name'])) ?>
				</span>
				<input
	                type="hidden"
	                name="<?= "{$name}[{$i}][name]" ?>"
	                value="<?= $this->escape($item['name']) ?>">
			<?php } ?>
			<textarea
				name="<?= "{$name}[{$i}][value]" ?>"
				id="<?= "{$id}[{$i}][value]" ?>"
				<?= isset($class) ? "class=\"{$class}\"" : null ?>
				<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
				<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
				<?= isset($disabled) && $disabled ? "disabled" : null ?>><?= $this->escape($item['value']) ?></textarea>
		</div>	
	<?php $items[] = $this->end() ?>
<?php } ?>
<? if (isset($stackable) ? $stackable : true) { ?>
	<?php $this->start() ?>
		<div class="form-group form-group-vertical">
			<input
	            type="text"
	            name="<?= "{$name}[{{i}}][name]" ?>"
	            id="<?= "{$id}[{{i}}][name]" ?>"
	            placeholder="Name"
	            value=""
	       		<?= isset($datalist) ? "list=\"{$id}_datalist\"" : null ?>
	            <?= (isset($disabled) && $disabled) || !$stackable ? "disabled" : null ?>
	            <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
			<textarea
				name="<?= "{$name}[{{i}}][value]" ?>"
				id="<?= "{$id}[{{i}}][value]" ?>"
				<?= isset($class) ? "class=\"{$class}\"" : null ?>
				<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
				<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
				<?= isset($disabled) && $disabled ? "disabled" : null ?>></textarea>
		</div>	
	<?php $template = $this->end() ?>
	<?= $this->child('stackable', [
		'items'		=> $items,
		'template'	=> $template,
	]) ?>
<? } else { ?>
	<?= implode(null, $items) ?>
<? } ?>
<? if (isset($datalist)) { ?>
	<datalist id="<?= "{$id}_datalist" ?>">
	    <?php foreach ($datalist as $value) { ?>
	        <option value="<?= $value ?>">
	    <?php } ?>
	</datalist>
<? } ?>