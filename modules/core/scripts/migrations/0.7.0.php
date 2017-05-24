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
        "RENAME TABLE {$name}_index TO {$name}_search",
        "DROP INDEX idx_d6f246e1fec530a9 ON {$name}_search",
        "DROP INDEX uniq_d6f246e11f08814af62829fc ON {$name}_search",
        "CREATE FULLTEXT INDEX IDX_14ACB8FEFEC530A9 ON {$name}_search (content)",
        "CREATE UNIQUE INDEX UNIQ_14ACB8FE1F08814AF62829FC ON {$name}_search (entityType, entityId)",
    ]);

};