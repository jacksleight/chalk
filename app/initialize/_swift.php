<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

$config = $app->config->mail;
if ($config['driver'] == 'mail') {
    $transport = Swift_MailTransport::newInstance();
} else if ($config['driver'] == 'smtp') {
    $transport = Swift_SmtpTransport::newInstance($config['host'], $config['port']);

    if (isset($config['encryption'])) {
        $transport->setEncryption($config['encryption']);
    }
    if (isset($config['user'])) {
        $transport->setUsername($config['user']);
    }
    if (isset($config['password'])) {
        $transport->setPassword($config['password']);
    }
    var_dump($transport);
}

return Swift_Mailer::newInstance($transport);