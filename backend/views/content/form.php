<div class="flex-col">
	<fieldset class="header">
		<?= $this->partial('tools') ?>
		<?= $this->partial('header') ?>
	</fieldset>
	<div class="flex body">
		<div class="hanging">
			<?= $this->partial('meta') ?>
		</div>
		<?= $this->partial('general') ?>
		<?= $this->partial('publishable') ?>
		<?= $this->partial('node') ?>
		<?= $this->partial('developer') ?>
	</div>
	<fieldset class="footer">
		<?= $this->partial('actions-primary') ?>
		<?= $this->partial('actions-secondary') ?>
	</fieldset>
</div>