<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

return function($conn, $em) {

    $name = $this->name();
    
    $conn->exec("
        ALTER TABLE core_content ADD core_page_imageId INT DEFAULT NULL
    ");
    $conn->exec("
        ALTER TABLE core_content ADD CONSTRAINT FK_ECD2859A47235100 FOREIGN KEY (core_page_imageId) REFERENCES core_content (id)
    ");
    $conn->exec("
        CREATE INDEX IDX_ECD2859A47235100 ON core_content (core_page_imageId)
    ");
    
};