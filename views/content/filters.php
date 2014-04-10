<form action="<?= $this->url->route() ?>" class="submitable">
	<ul class="filters">
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'input_search',
				'entity'		=> $filter,
				'name'			=> 'search',
				'placeholder'	=> 'Search…',
			)) ?>
		</li>
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'dropdown_single',
				'entity'		=> $filter,
				'null'			=> 'Any',
				'name'			=> 'createDateMin',
				'icon'			=> 'calendar',
				'placeholder'	=> 'Date Added',
			)) ?>
		</li>
		<li>
			<?= $this->render('/elements/form-input', array(
				'type'			=> 'dropdown_multiple',
				'entity'		=> $filter,
				'name'			=> 'createUsers',
				'icon'			=> 'user',
				'placeholder'	=> 'Added By',
				'values'		=> []
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