<?php
$this->params([
    'tableCols' => $tableCols = [
        'enabled' => [
            'label'   => 'Enabled',
            'class'   => 'col-badge',
            'partial' => 'enabled',
        ],
        'name' => [
            'label'   => 'Name',
            'partial' => 'name',
        ],
        'role' => [
            'label'   => 'Role',
            'class'   => 'col-right col-contract',
            'func'    => function($entity, $params) {
                return ucwords($entity->role);
            },
        ],
    ] + (isset($tableCols) ? $tableCols : []),
]);
?>
<?= $this->parent() ?>