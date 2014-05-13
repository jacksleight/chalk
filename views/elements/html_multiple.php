<? foreach ($entity->{$name} as $label => $value) { ?>
	<div class="form-item optional">
		<label>
			<?= ucfirst($label) ?>
		</label>
		<div>
			<textarea
				name="<?= "{$md['contextName']}[{$label}]" ?>"
				id="<?= "_{$md['contextName']}[{$label}]" ?>"
				<?= isset($disabled) && $disabled ? "disabled" : null ?>
				class="html"><?= $this->escape($value) ?></textarea>
		</div>
	</div>	
<? } ?>