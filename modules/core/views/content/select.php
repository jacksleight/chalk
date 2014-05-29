<?= $this->render('/content/browser', [
	'close'			=> true,
	'thumbs'		=> true,
	'selectOnly'	=> true,
	'entityType'	=> \Ayre::type('core_file'),
]) ?>