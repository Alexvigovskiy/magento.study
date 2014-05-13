<?php

$installer = $this;
$installer->startSetup();
$installer->getConnection()
        ->addColumn(
                "sales_flat_order", "exported", array(
            'nullable' => true,
            'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'comment' => 'ExpOrder'
                )
);
$installer->endSetup();
