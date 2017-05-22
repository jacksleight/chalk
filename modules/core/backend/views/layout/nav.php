<?php
if (!$items) {
    return;
}
?>
<ul class="<?= isset($class) ? $class : null ?>">
    <?php foreach ($items as $name => $item) { ?>
        <?php
        if (!isset($item)) {
            continue;
        }
        if (isset($item['roles']) && !in_array($req->user->role, $item['roles'])) {
            continue;
        }
        $class  = [
            $item['isActive']     ? 'active' : null,
            $item['isActivePath'] ? 'active-path' : null,
        ];
        $url = $item['url'];
        $url->queryParams([
            'mode' => $req->mode,
        ]);
        ?>
        <li class="<?= implode(' ', $class) ?>">
            <a href="<?= $url ?>" class="item <?= implode(' ', $class) ?>">
                <?php if (isset($item['label'])) { ?>
                    <?php if (isset($item['icon-block'])) { ?>
                        <span class="icon-block icon-<?= $item['icon-block'] ?>">
                            <span class="icon-block-text"><?= $item['label'] ?></span>
                        </span>
                    <?php } else if (isset($item['icon'])) { ?>
                        <span class="icon-<?= $item['icon'] ?>"></span>
                        <?= $item['label'] ?>
                    <?php } else { ?>
                        <?= $item['label'] ?>
                    <?php } ?>                  
                <?php } ?>
                <?php if (isset($item['badge'])) { ?>
                    <span class="badge badge-figure badge-pending"><?= $item['badge'] ?></span>
                <?php } ?>
            </a>
            <?php if ($item['isTagable']) { ?>
                <?= $this->inner('tags', ['item' => $item, 'url' => $url]) ?>
            <?php } ?>
            <?php
            $children = $this->nav->children($name);
            ?>
            <?php if (count($children)) { ?>
                <?= $this->inner('nav', [
                    'class' => null,
                    'items' => $children,
                ]) ?>
            <?php } ?>
        </li>
    <?php } ?>
</ul>