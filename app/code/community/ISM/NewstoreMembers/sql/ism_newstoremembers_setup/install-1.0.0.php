<?php
/**
 * Add member_number attribute for customer.
 */
$this->startSetup();
$this->getConnection()
        ->addColumn(
                'customer_entity', 'member_number', array(
    'type' => 'text', /* input type */
    'label' => 'Member number', /* Label for the user to read */
    'input' => 'text', /* input type */
    'visible' => TRUE, /* users can see it */
    'required' => FALSE, /* is it required, self-explanatory */
    'default_value' => 'default', /* default value */
        )
);
/*$this->run("
-- DROP TABLE IF EXISTS {$this->getTable('newstoremembers')};
CREATE TABLE {$this->getTable('newstoremembers')} (
`member_id` int(11) unsigned NOT NULL auto_increment,
`member` varchar(255) NOT NULL default '',
`member_number` varchar(255) NOT NULL default '',
`status` smallint(6) NOT NULL default '0',
PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");*/
$this->endSetup();
