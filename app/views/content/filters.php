<ul class="filters autosubmitable">
	<li>
		<?= $this->render('/element/form-input', array(
			'type'			=> 'input_search',
			'entity'		=> $filter,
			'name'			=> 'search',
			'autofocus'		=> true,
			'placeholder'	=> 'Search…',
		)) ?>
	</li>
	<li>
		<?= $this->render('/element/form-input', array(
			'type'			=> 'dropdown_single',
			'entity'		=> $filter,
			'null'			=> 'Any',
			'name'			=> 'modifyDateMin',
			'icon'			=> 'icon-updated',
			'placeholder'	=> 'Updated',
		)) ?>
	</li>
	<li>
		<?= $this->render('/element/form-input', array(
			'type'			=> 'dropdown_multiple',
			'entity'		=> $filter,
			'name'			=> 'statuses',
			'icon'			=> 'icon-status',
			'placeholder'	=> 'Status',
		)) ?>
	</li>
</ul>