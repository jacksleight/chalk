<?php
$this->params([
    'tableCols' => $tableCols = (isset($tableCols) ? $tableCols : []) + [
        'name' => [
            'label'   => 'Name',
            'partial' => 'preview',
            'params'  => ['icon' => true],
            'sort'    => 10,
        ],
    ],
]);
?>
<?= $this->parent() ?>