<span class="search icon-search">
	<?php
	echo $this->render('input', [
		'type'		=> 'search',
		'value'		=> $entity->{$name},
		'maxlength'	=> isset($md['length']) ? $md['length'] : null,
	]);
	?>
</span>