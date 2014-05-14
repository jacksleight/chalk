<? foreach ($entity->{$name} as $label => $value) { ?>
	<div class="form-item optional">
		<label>
			<?= ucfirst($label) ?>
		</label>
		<div>
			<textarea
				name="<?= "{$md['contextName']}[{$label}]" ?>"
				id="<?= "_{$md['contextName']}[{$label}]" ?>"
				<?= isset($class) ? "class=\"{$class}\"" : null ?>
				<?= isset($rows) ? "rows=\"{$rows}\"" : null ?>
				<?= isset($cols) ? "cols=\"{$cols}\"" : null ?>
				<?= isset($disabled) && $disabled ? "disabled" : null ?>><?= $this->escape($value) ?></textarea>
		</div>
	</div>	
<? } ?>