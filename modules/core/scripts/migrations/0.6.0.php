<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

return function() {

    $em   = $this->app->em;
    $conn = $em->getConnection();

    $conn->exec("
        CREATE TABLE core_setting (
            name VARCHAR(255) NOT NULL,
            value LONGTEXT DEFAULT NULL,
            PRIMARY KEY(name)
        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
    ");

};