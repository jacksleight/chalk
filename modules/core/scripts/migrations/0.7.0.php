<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

return function($conn, $em) {

    array_map([$conn, 'exec'], [
        "ALTER TABLE core_content
            ADD core_page_imageId INT DEFAULT NULL",
        "ALTER TABLE core_content
            ADD CONSTRAINT FK_ECD2859A47235100 FOREIGN KEY (core_page_imageId) REFERENCES core_content (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_ECD2859A47235100 ON core_content (core_page_imageId)",
    ]);

    array_map([$conn, 'exec'], [
        "ALTER TABLE core_content DROP FOREIGN KEY FK_ECD2859A7724C6C3",
        "DROP INDEX IDX_ECD2859A7724C6C3 ON core_content",
        "ALTER TABLE `core_content`
            CHANGE `core_alias_contentId` `core_alias_entity` VARCHAR(255) DEFAULT NULL COMMENT '(DC2Type:chalk_entity)'",
        "UPDATE core_content SET core_alias_entity = CONCAT('{\"type\":\"core_content\",\"id\":', core_alias_entity, '}') WHERE core_alias_entity IS NOT NULL",
    ]);

    array_map([$conn, 'exec'], [
        "RENAME TABLE core_index TO core_search",
        "DROP INDEX idx_d6f246e1fec530a9 ON core_search",
        "DROP INDEX uniq_d6f246e11f08814af62829fc ON core_search",
        "CREATE FULLTEXT INDEX IDX_14ACB8FEFEC530A9 ON core_search (content)",
        "CREATE UNIQUE INDEX UNIQ_14ACB8FE1F08814AF62829FC ON core_search (entityType, entityId)",
    ]);

    array_map([$conn, 'exec'], [
        "ALTER TABLE core_content DROP FOREIGN KEY FK_ECD2859A9B648F1F",
        "DROP INDEX IDX_ECD2859A9B648F1F ON core_content",
        "ALTER TABLE core_content CHANGE modifydate updateDate DATETIME DEFAULT NULL, CHANGE modifyuserid updateUserId INT DEFAULT NULL",
        "ALTER TABLE core_content ADD CONSTRAINT FK_ECD2859A894646E4 FOREIGN KEY (updateUserId) REFERENCES core_user (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_ECD2859A894646E4 ON core_content (updateUserId)",
        "ALTER TABLE core_domain DROP FOREIGN KEY FK_7F57D529B648F1F",
        "DROP INDEX IDX_7F57D529B648F1F ON core_domain",
        "ALTER TABLE core_domain CHANGE modifydate updateDate DATETIME DEFAULT NULL, CHANGE modifyuserid updateUserId INT DEFAULT NULL",
        "ALTER TABLE core_domain ADD CONSTRAINT FK_7F57D52894646E4 FOREIGN KEY (updateUserId) REFERENCES core_user (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_7F57D52894646E4 ON core_domain (updateUserId)",
        "ALTER TABLE core_log DROP FOREIGN KEY FK_B290BEE99B648F1F",
        "DROP INDEX IDX_B290BEE99B648F1F ON core_log",
        "ALTER TABLE core_log CHANGE modifydate updateDate DATETIME DEFAULT NULL, CHANGE modifyuserid updateUserId INT DEFAULT NULL",
        "ALTER TABLE core_log ADD CONSTRAINT FK_B290BEE9894646E4 FOREIGN KEY (updateUserId) REFERENCES core_user (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_B290BEE9894646E4 ON core_log (updateUserId)",
        "ALTER TABLE core_structure DROP FOREIGN KEY FK_206107CE9B648F1F",
        "DROP INDEX IDX_206107CE9B648F1F ON core_structure",
        "ALTER TABLE core_structure CHANGE modifydate updateDate DATETIME DEFAULT NULL, CHANGE modifyuserid updateUserId INT DEFAULT NULL",
        "ALTER TABLE core_structure ADD CONSTRAINT FK_206107CE894646E4 FOREIGN KEY (updateUserId) REFERENCES core_user (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_206107CE894646E4 ON core_structure (updateUserId)",
        "ALTER TABLE core_tag DROP FOREIGN KEY FK_3E2661AF9B648F1F",
        "DROP INDEX IDX_3E2661AF9B648F1F ON core_tag",
        "ALTER TABLE core_tag CHANGE modifydate updateDate DATETIME DEFAULT NULL, CHANGE modifyuserid updateUserId INT DEFAULT NULL",
        "ALTER TABLE core_tag ADD CONSTRAINT FK_3E2661AF894646E4 FOREIGN KEY (updateUserId) REFERENCES core_user (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_3E2661AF894646E4 ON core_tag (updateUserId)",
        "ALTER TABLE core_user DROP FOREIGN KEY FK_BF76157C9B648F1F",
        "DROP INDEX IDX_BF76157C9B648F1F ON core_user",
        "ALTER TABLE core_user CHANGE modifydate updateDate DATETIME DEFAULT NULL, CHANGE modifyuserid updateUserId INT DEFAULT NULL",
        "ALTER TABLE core_user ADD CONSTRAINT FK_BF76157C894646E4 FOREIGN KEY (updateUserId) REFERENCES core_user (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_BF76157C894646E4 ON core_user (updateUserId)",
    ]);

};