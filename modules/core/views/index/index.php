<?php
if (!$req->isAjax()) {
	$this->layout('/layouts/page');
}
?>
<? $this->block('main') ?>