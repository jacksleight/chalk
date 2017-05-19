<?php
$tags     = $this->em('core_tag')->all();
$tagsList = $req->tagsList;
$isNone   = false;
if ($tagsList == 'none') {
    $isNone = true;
    $tagsList = [];
} else if (isset($tagsList)) {
    $tagsList = explode('.', $tagsList);
} else {
    $tagsList = [];
}
?>
<ul class="tags">
    <?php foreach ($tags as $tag) { ?>
        <li>
            <?php if (in_array($tag->id, $tagsList)) { ?>
                <?php
                $temp = $tagsList;
                if ($req->action == 'index') {
                    unset($temp[array_search($tag->id, $temp)]);
                }
                ?>
                <a href="<?= $item['url'] . $this->url->query([
                    'tagsList' => implode('.', $temp),
                ]) ?>" class="active">
                    <?= $tag->name ?>
                </a>
            <?php } else { ?>
                <?php
                $temp = $tagsList;
                if ($req->action == 'index') {
                    $temp = array_merge($temp, [$tag->id]);
                }
                ?>
                <a href="<?= $item['url'] . $this->url->query([
                    'tagsList' => implode('.', $temp),
                ]) ?>">
                    <?= $tag->name ?>
                </a>
            <?php } ?>
        </li>
    <?php } ?>
    <li>
        <a href="<?= $item['url'] . $this->url->query([
            'tagsList' => $isNone ? '' : 'none',
        ]) ?>" class="<?= $isNone ? 'active' : null ?>">
            Untagged
        </a>
    </li>
</ul>