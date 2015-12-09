<?php
$this->params([
    'filterFields' => $filterFields = isset($filterFields) ? $filterFields : [
        [
            'class'   => 'flex-2',
            'partial' => 'date-min',
            'params'  => ['property' => 'modify', 'placeholder' => 'Updated'],
        ],
    ],
]);
?>
<?= $this->parent() ?>