<?php
$this->params([
    'tableCols' => $tableCols = [
        'name' => [
            'label'   => 'Name',
            'partial' => 'name',
        ],
    ] + (isset($tableCols) ? $tableCols : []),
]);
?>
<?= $this->parent() ?>