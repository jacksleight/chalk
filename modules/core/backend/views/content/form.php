<div class="flex-col">
	<fieldset class="header">
		<?= $this->partial('tools') ?>
		<?= $this->partial('header') ?>
		<div class="hanging">
			<?= $this->partial('meta') ?>
		</div>
	</fieldset>
	<div class="flex body">
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