<div class="flex-col">
	<div class="flex body">
		<?= $this->partial('tools') ?>
		<?= $this->partial('header') ?>
		<?= $this->partial('meta') ?>
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