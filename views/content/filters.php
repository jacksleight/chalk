<form action="<?= $this->url->route() ?>" class="autosubmitable">
	<ul class="filters">
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'input_search',
				'entity'		=> $filter,
				'name'			=> 'search',
				'autofocus'		=> true,
				'placeholder'	=> 'Search…',
			)) ?>
		</li>
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'dropdown_single',
				'entity'		=> $filter,
				'null'			=> 'Any',
				'name'			=> 'modifyDateMin',
				'icon'			=> 'calendar',
				'placeholder'	=> 'Updated',
			)) ?>
		</li>
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'dropdown_multiple',
				'entity'		=> $filter,
				'name'			=> 'statuses',
				'icon'			=> 'check-circle',
				'placeholder'	=> 'Status',
			)) ?>
		</li>
	</ul>
</form>