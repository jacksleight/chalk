<?php
$this->params([
    'bodyType' => $bodyType = isset($bodyType) ? $bodyType : 'thumbs',
]);
?>
<?= $this->parent() ?>