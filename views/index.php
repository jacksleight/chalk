<?php
$path = '/layouts/page' . (isset($page->layout)
	? '/' . $page->layout
	: null);
$this->layout($path);
foreach ($page->content as $name => $content) {
	$this->block($name);
	echo $content;
}