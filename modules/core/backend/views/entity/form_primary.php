<fieldset class="form-block">
    <div class="form-items">
        <?= $this->partial('primary-top') ?>
        <?= $this->partial('primary-tagable') ?>
        <?= $this->partial('primary-bottom') ?>
        <?php $this->start() ?>
            <?= $this->partial('primary-advanced-top') ?>
            <?= $this->partial('primary-publishable') ?>
            <?= $this->partial('primary-advanced-bottom') ?>
        <?php $html = $this->end() ?>
        <?= $this->render('/element/expandable', [
            'content'       => $html,
            'buttonLabel'   => 'Advanced',
        ], 'core') ?>
    </div>
</fieldset>