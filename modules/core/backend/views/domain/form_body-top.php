<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'label',
	'label'		=> 'Title',
	'autofocus'	=> true,
)) ?>
<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'name',
	'label'		=> 'Domain',
)) ?>
<?= $this->render('/element/form-item', array(
	'entity'	=> $entity,
	'name'		=> 'emailAddress',
	'label'		=> 'Email Address',
)) ?>
<?php if ($req->user->isDeveloper()) { ?>
	<?= $this->render('/element/form-item', array(
		'entity'	=> $entity,
		'name'		=> 'structures',
		'label'		=> 'Structures',
		'values'    => $this->em('Chalk\Core\Structure')->all(),
	)) ?>
<?php } ?>