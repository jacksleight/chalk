<?php
$params = [
    'title' => $req->node['depth'] > 0 ? $page->name : null,
    'metas' => [
        'description' => $this->strip($page->summary)
    ],
];
$config = $this->chalk->config->layoutScripts;
$layout = $config[0] . (isset($page->layout) ? "/{$page->layout}" : "/default");
$this->outer($layout, $params, $config[1]);

foreach ($page->blocks as $block) {
    $this->block($block['name']);
    echo $block['value'];
}