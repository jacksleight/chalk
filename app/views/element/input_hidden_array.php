<?php
$fields = explode('&', http_build_query([$name => $value]));
$inputs = [];
foreach ($fields as $field) {
	list($name, $value) = explode('=', $field);
	$inputs[urldecode($name)] = urldecode($value);
}
?>
<? foreach ($inputs as $name => $value) { ?>
	<input
		type="hidden"
		name="<?= $this->escape($name) ?>"
		value="<?= $this->escape($value) ?>">
<? } ?>