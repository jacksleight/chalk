<?php
$this->params([
    'tableCols' => $tableCols = (isset($tableCols) ? $tableCols : []) + [
        'enabled' => [
            'label'   => 'Enabled',
            'class'   => 'col-badge',
            'partial' => 'enabled',
            'sort'    => 0,
        ],
        'role' => [
            'label'   => 'Role',
            'class'   => 'col-right col-contract',
            'func'    => function($entity, $params) {
                return ucwords($entity->role);
            },
            'sort'    => 90,
        ],
    ],
]);
?>
<?= $this->parent() ?>