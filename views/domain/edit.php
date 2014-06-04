<? $this->layout('/layouts/page_settings') ?>
<? $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="fill">
	<div class="flex">
		<h1>
			<? if (!$domain->isNew()) { ?>
				<?= $domain->name ?>
			<? } else { ?>
				New <?= $entityType->singular ?>
			<? } ?>
		</h1>
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/elements/form-item', array(
					'entity'	=> $domain,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
				)) ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="fix">
		<ul class="toolbar">
			<li>
				<button class="btn-focus">
					<i class="fa fa-check"></i>
					Save <?= $entityType->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<? if (!$domain->isNew()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-quiet confirmable">
						<i class="fa fa-trash-o"></i> Delete <?= $entityType->singular ?>
					</a>
				</li>
			<? } ?>
		</ul>
	</fieldset>
</form>