<?php
$this->params([
    'isAddAllowed'    => $isAddAllowed    = isset($isAddAllowed)    ? $isAddAllowed    : false,
    'isDeleteAllowed' => $isDeleteAllowed = isset($isDeleteAllowed) ? $isDeleteAllowed : false,
]);
?>
<?= $this->parent() ?>