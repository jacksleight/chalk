<fieldset class="form-block">
    <div class="form-items">
        <?= $this->partial('body-top') ?>
        <?= $this->partial('body-tagable') ?>
        <?= $this->partial('body-bottom') ?>
        <? $this->start() ?>
            <?= $this->partial('body-advanced-top') ?>
            <?= $this->partial('body-publishable') ?>
            <?= $this->partial('body-advanced-bottom') ?>
        <? $html = $this->end() ?>
        <?= $this->render('/element/expandable', [
            'content'       => $html,
            'buttonLabel'   => 'Advanced',
        ], 'core') ?>   
    </div>
</fieldset>