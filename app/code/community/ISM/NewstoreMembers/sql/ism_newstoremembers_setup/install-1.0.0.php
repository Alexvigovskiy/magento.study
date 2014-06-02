<?php

$this->startSetup();
$this->run("
-- DROP TABLE IF EXISTS {$this->getTable('newstoremembers')};
CREATE TABLE {$this->getTable('newstoremembers')} (
`member_id` int(11) unsigned NOT NULL auto_increment,
`user_id` int(10),
`member_number` varchar(255) NOT NULL default '',
`post_code` varchar(255) NOT NULL default '',
`expire_date` datetime NULL,
`status` smallint(6) NOT NULL default '0',
PRIMARY KEY (`member_id`),
FOREIGN KEY (`user_id`) REFERENCES `magento`.`customer_entity` (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$this->endSetup();
