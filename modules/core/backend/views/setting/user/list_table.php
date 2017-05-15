<?php
$this->params([
    'tableCols' => $tableCols = isset($tableCols) ? $tableCols : [
        [
            'label'   => 'Enabled',
            'class'   => 'col-badge',
            'partial' => 'enabled',
        ],
        [
            'label'   => 'Name',
            'partial' => 'name',
        ],
        [
            'label'   => 'Role',
            'class'   => 'col-right col-contract',
            'func'    => function($entity, $params) {
            	return ucwords($entity->role);
            },
        ],
    ],
]);
?>
<?= $this->parent() ?>