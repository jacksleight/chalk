<header class="navbar navbar-default"><div class="container">
	<a class="navbar-brand" href="<?= $this->url(array(), 'index', true) ?>"><?= $this->locale->message('title') ?></a>
	<?= $this->render('_nav', array('items' => [
		'user'		=> [],
		'category'	=> [],
		'article'	=> [],
	], 'class' => 'nav navbar-nav pull-right')) ?>
</div></header>
<?= $content[0] ?>
<footer class="footer c" role="contentinfo"><div class="container">
	<p>© <?= date('Y') ?> <?= $this->locale->message('title') ?></p>
</div></footer>