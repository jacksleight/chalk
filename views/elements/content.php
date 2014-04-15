<? foreach ($entity->{$name} as $label => $value) { ?>
	<div class="form-item optional">
		<label>
			<?= ucfirst($label) ?>
		</label>
		<div>
			<textarea
				name="<?= implode('_', $md['context']) . "[{$label}]" ?>"
				id="<?= '_' . implode('_', $md['context']) . "[{$label}]" ?>"
				class="html"><?= $this->escape($value) ?></textarea>
		</div>
	</div>	
<? } ?>