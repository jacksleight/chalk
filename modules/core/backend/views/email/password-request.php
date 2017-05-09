Hi <?= $user->name ?>,

We've received a request to reset your <?= $domain->label ?> password.

Please click the link below to reset your password:

<?= $this->url([
	'token' => $user->token,
], 'core_passwordReset', true) . "\n" ?>
This link will expire in 24 hours.

If you did not make this request please ignore this email.