<?php 
$items = $entity->{$name};
$stackable = isset($stackable) && $stackable;
?>
<? if ($stackable) { ?>
	<div class="stackable">
		<div class="stackable-list">
<? } ?>
<? foreach ($items as $i => $item) { ?>
	<div class="stackable-item form-item">
		<label>
			<? if ($stackable) { ?>
				<input
                    type="text"
                    name="<?= "{$md['contextName']}[{$i}][name]" ?>"
                    id="<?= "_{$md['contextName']}[{$i}][name]" ?>"
                    placeholder="Name"
                    value="<?= $this->escape($item['name']) ?>"
                    <?= isset($disabled) && $disabled ? "disabled" : null ?>
                    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
			<? } else { ?>
				<?= ucwords(\Coast\str_camel_split($item['name'])) ?>
			<? } ?>
		</label>
		<div>
			<textarea
				name="<?= "{$md['contextName']}[{$i}][value]" ?>"
				id="<?= "_{$md['contextName']}[{$i}][value]" ?>"
				<?= isset($class) ? "class=\"{$class}\"" : null ?>
				<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
				<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
				<?= isset($disabled) && $disabled ? "disabled" : null ?>><?= $this->escape($item['value']) ?></textarea>
		</div>
	</div>	
<? } ?>
<? if ($stackable) { ?>
	    </div>
	    <span class="btn stackable-button">
	        <i class="fa fa-plus"></i>
	        Add
	    </span>
	    <script type="x-tmpl-mustache" class="stackable-template">
	         <div class="stackable-item form-item optional">
				<label>
					<input
	                    type="text"
	                    name="<?= "{$md['contextName']}[{{i}}][name]" ?>"
	                    id="<?= "_{$md['contextName']}[{{i}}][name]" ?>"
	                    placeholder="Name"
	                    value=""
	                    <?= isset($disabled) && $disabled ? "disabled" : null ?>
	                    <?= isset($readOnly) && $readOnly ? "readonly" : null ?>>
				</label>
				<div>
					<textarea
						name="<?= "{$md['contextName']}[{{i}}][value]" ?>"
						id="<?= "_{$md['contextName']}[{{i}}][value]" ?>"
						<?= isset($class) ? "class=\"{$class}\"" : null ?>
						<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
						<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
						<?= isset($disabled) && $disabled ? "disabled" : null ?>></textarea>
				</div>
			</div>	
	    </script>
	</div>
<? } ?>