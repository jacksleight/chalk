<?php if ($req->user->isDeveloper()) { ?>
	<fieldset class="form-block">
		<div class="form-legend">
			<h2>Developer</h2>
		</div>
		<div class="form-items">
			<?= $this->render('/element/form-item', array(
				'entity'		=> $content,
				'name'			=> 'isProtected',
				'label'			=> 'Protected',
			)) ?>	
			<?php $this->start() ?>
				<?= $this->render('/element/form-item', array(
					'entity'		=> $content,
					'name'			=> 'id',
					'label'			=> 'Content ID',
					'type'			=> 'input_pseudo',
					'readOnly'		=> true,
				)) ?>
				<?= $this->render('/element/form-item', array(
					'entity'		=> $content,
					'name'			=> 'slug',
					'label'			=> 'Content Slug',
					'type'			=> 'input_pseudo',
					'readOnly'		=> true,
				)) ?>
				<?php if (isset($node)) { ?>
					<?= $this->render('/element/form-item', array(
						'entity'		=> $node,
						'name'			=> 'id',
						'label'			=> 'Node ID',
						'type'			=> 'input_pseudo',
						'readOnly'		=> true,
					)) ?>
					<?= $this->render('/element/form-item', array(
						'entity'		=> $node,
						'name'			=> 'slug',
						'label'			=> 'Node Slug',
						'type'			=> 'input_pseudo',
						'readOnly'		=> true,
					)) ?>
					<?= $this->render('/element/form-item', array(
						'entity'		=> $node,
						'name'			=> 'path',
						'label'			=> 'Node Path',
						'type'			=> 'input_pseudo',
						'readOnly'		=> true,
					)) ?>
				<?php } ?>
			<?php $html = $this->end() ?>
			<?= $this->render('/element/expandable', [
				'content'		=> $html,
				'buttonLabel'	=> 'Information',
			], 'core') ?>
		</div>
	</fieldset>
<?php } ?>