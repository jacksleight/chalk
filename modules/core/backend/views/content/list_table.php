<?php
$this->params([
    'tableCols' => $tableCols = [
        'name' => [
            'label'   => 'Name',
            'partial' => 'name',
            'sort'    => 1,
        ],
    ] + (isset($tableCols) ? $tableCols : []),
]);
?>
<?= $this->parent() ?>