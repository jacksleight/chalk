<?php
$params = [
    'title' => $req->node['depth'] > 0 ? $page->name : null,
    'metas' => [
        'description' => $this->strip($page->summary)
    ],
];
$layout = '/layouts/page' . (isset($page->layout) ? "/{$page->layout}" : "/default");
$this->outer($layout, $params, 'default');

foreach ($page->blocks as $block) {
    $this->block($block['name']);
    echo $block['value'];
}