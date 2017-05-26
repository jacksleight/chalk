<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

return function($conn, $em) {

    $name = $this->name();
    
    array_map([$conn, 'exec'], [
        "ALTER TABLE {$name}_content
            ADD {$name}_page_imageId INT DEFAULT NULL",
        "ALTER TABLE {$name}_content
            ADD CONSTRAINT FK_ECD2859A47235100 FOREIGN KEY ({$name}_page_imageId) REFERENCES {$name}_content (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_ECD2859A47235100 ON {$name}_content ({$name}_page_imageId)",
    ]);

    array_map([$conn, 'exec'], [
        "ALTER TABLE {$name}_content DROP FOREIGN KEY FK_ECD2859A7724C6C3",
        "DROP INDEX IDX_ECD2859A7724C6C3 ON {$name}_content",
        "ALTER TABLE `{$name}_content`
            CHANGE `{$name}_alias_contentId` `{$name}_alias_link` VARCHAR(255) DEFAULT NULL COMMENT '(DC2Type:chalk_entity)'",
        "UPDATE {$name}_content SET {$name}_alias_link = CONCAT('{\"type\":\"{$name}_content\",\"id\":', {$name}_alias_link, '}') WHERE {$name}_alias_link IS NOT NULL",
    ]);

    array_map([$conn, 'exec'], [
        "RENAME TABLE {$name}_index TO {$name}_search",
        "DROP INDEX idx_d6f246e1fec530a9 ON {$name}_search",
        "DROP INDEX uniq_d6f246e11f08814af62829fc ON {$name}_search",
        "CREATE FULLTEXT INDEX IDX_14ACB8FEFEC530A9 ON {$name}_search (content)",
        "CREATE UNIQUE INDEX UNIQ_14ACB8FE1F08814AF62829FC ON {$name}_search (entityType, entityId)",
    ]);

};