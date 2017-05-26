<?php
$this->outer('/layouts/html', [
    'jump'  => $page,
    'title' => $req->node['depth'] > 0 ? $page->name : null,
    'metas' => [
        'description' => $this->strip($page->summary)
    ],
], '__Chalk__core');

foreach ($page->blocks as $block) {
    $this->block($block['name']);
    echo $block['value'];
}