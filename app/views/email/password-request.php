Hi <?= $user->name ?>,

We've received a request to reset your <?= $this->chalk->config->name ?> CMS password.

Please click the link below to reset your password:

<?= $this->req->url()->toPart(\Coast\Url::PART_PORT) ?><?= $this->url([
	'token' => $user->token,
], 'passwordReset', true) . "\n" ?>
This link will expire in 24 hours.

If you did not send this request please ignore this email.