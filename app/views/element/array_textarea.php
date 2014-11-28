<?php
$items = [];
?>
<?php foreach ($entity->{$name} as $i => $item) { ?>
	<?php $this->start() ?>
		<div class="form-group form-group-vertical">
			<?php if ($stackable) { ?>
				<input
	                type="text"
	                name="<?= "{$md['contextName']}[{$i}][name]" ?>"
	                id="<?= "_{$md['contextName']}[{$i}][name]" ?>"
	                placeholder="Name"
	                value="<?= $this->escape($item['name']) ?>"
	                <?= isset($datalist) ? "list=\"_{$md['contextName']}_datalist\"" : null ?>
	                <?= isset($disabled) && $disabled ? "disabled" : null ?>
	                <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
			<?php } else { ?>
				<span class="value disabled">
					<?= ucwords(\Coast\str_camel_split($item['name'])) ?>
				</span>
				<input
	                type="hidden"
	                name="<?= "{$md['contextName']}[{$i}][name]" ?>"
	                value="<?= $this->escape($item['name']) ?>">
			<?php } ?>
			<textarea
				name="<?= "{$md['contextName']}[{$i}][value]" ?>"
				id="<?= "_{$md['contextName']}[{$i}][value]" ?>"
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
	            name="<?= "{$md['contextName']}[{{i}}][name]" ?>"
	            id="<?= "_{$md['contextName']}[{{i}}][name]" ?>"
	            placeholder="Name"
	            value=""
	       		<?= isset($datalist) ? "list=\"_{$md['contextName']}_datalist\"" : null ?>
	            <?= (isset($disabled) && $disabled) || !$stackable ? "disabled" : null ?>
	            <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
			<textarea
				name="<?= "{$md['contextName']}[{{i}}][value]" ?>"
				id="<?= "_{$md['contextName']}[{{i}}][value]" ?>"
				<?= isset($class) ? "class=\"{$class}\"" : null ?>
				<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
				<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
				<?= isset($disabled) && $disabled ? "disabled" : null ?>></textarea>
		</div>	
	<?php $template = $this->end() ?>
	<?= $this->render('stackable', [
		'items'		=> $items,
		'template'	=> $template,
	]) ?>
<? } else { ?>
	<?= implode(null, $items) ?>
<? } ?>
<? if (isset($datalist)) { ?>
	<datalist id="<?= "_{$md['contextName']}_datalist" ?>">
	    <?php foreach ($datalist as $value) { ?>
	        <option value="<?= $value ?>">
	    <?php } ?>
	</datalist>
<? } ?>