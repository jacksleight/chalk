<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

return function($conn, $em) {

    $name = $this->name();

    $conn->exec("
        CREATE TABLE core_setting (
            name VARCHAR(255) NOT NULL,
            value LONGTEXT DEFAULT NULL,
            PRIMARY KEY(name)
        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
    ");
    
    $conn->exec("
        ALTER TABLE core_domain
            ADD `label` VARCHAR(255) NOT NULL,
            ADD emailAddress VARCHAR(255) NOT NULL
    ");
    
    $domain = $em('core_domain')->id(1);
    $domain->fromArray([
        'label'        => $this->config->name,
        'emailAddress' => $this->config->emailAddress,
    ]);
    $em->flush();

};