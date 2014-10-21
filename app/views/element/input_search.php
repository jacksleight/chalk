<span class="search">
	<i class="fa fa-search"></i>
	<?php
	echo $this->render('input', [
		'type'		=> 'search',
		'value'		=> $entity->{$name},
		'maxlength'	=> isset($md['length']) ? $md['length'] : null,
	]);
	?>
</span>