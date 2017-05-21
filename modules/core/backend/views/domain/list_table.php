<?php
$this->params([
    'tableCols' => $tableCols = (isset($tableCols) ? $tableCols : []) + [
        'name' => [
            'label'   => 'Name',
            'partial' => 'name',
            'sort'    => 10,
        ],
    ],
]);
?>
<?= $this->parent() ?>