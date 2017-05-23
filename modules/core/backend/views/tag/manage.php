<?php $this->outer('/layout/page') ?>
<?php $this->block('main') ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col" data-modal-size="800x600">
	<div class="header">
		<h1>
			Manage Tags
		</h1>
	</div>
	<div class="flex body">
		<fieldset class="form-block">
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $model,
					'name'		=> 'mode',
					'label'		=> 'Add/Remove',
					'type'		=> 'input_radio',
				)) ?>
			    <?= $this->render('/element/form-item', array(
			        'autofocus' => true,
			        'type'      => 'input_tag',
			        'entity'    => $model,
			        'name'      => 'tagNamesList',
			        'label'     => 'Tags',
			        'values'    => $this->em('core_tag')->all(),
			    ), 'core') ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok">
					Save Changes
				</button>
			</li>
		</ul>
	</fieldset>
    <?= $this->render('/element/form-input', array(
        'type'   => 'input_hidden',
        'entity' => $model,
        'name'   => 'selectedList',
    ), 'core') ?>
    <?= $this->render('/element/form-input', array(
        'type'   => 'input_hidden',
        'entity' => $model,
        'name'   => 'selectedType',
    ), 'core') ?>
</form>