<?php
$title = (isset($title) 
    ? $title . ' – '
    : null) . $this->ck->home['name'];
$metas = isset($metas) 
    ? $metas
    : [];
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <title><?= $this->escape($title) ?></title>
    <?php foreach ($metas as $name => $value) { ?>
        <meta name="<?= $this->escape($name) ?>" content="<?= $this->escape($value) ?>">
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $this->ck->domain['head'] ?>
    <?= isset($head) ? $head : null ?>
</head>
<body>
    <?= $this->content() ?>
    <?= $this->ck->domain['body'] ?>
    <?= isset($body) ? $body : null ?>
</body>
</html>