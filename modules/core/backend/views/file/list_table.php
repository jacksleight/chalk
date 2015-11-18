<?php
$this->params([
    'tableCols' => $tableCols = isset($tableCols) ? $tableCols : [
        [
            'label'   => 'Created',
            'class'   => 'col-right col-contract',
            'partial' => 'date-user',
            'params'  => ['property' => 'create'],
        ],
    ],
]);
?>
<?= $this->parent() ?>