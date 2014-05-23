<?php
if (!$req->isAjax()) {
	$this->layout('/layouts/page');
}
?>
<? $this->block('main') ?>

<a href="<?= $this->url([]) ?>" class="btn" rel="modal">Open</a>
<a href="<?= $this->url([]) ?>" class="btn">Link 1</a>
<a href="<?= $this->url(['action' => 'respond']) ?>" class="btn">Link 2</a>
<span class="btn modal-close">Close</a>