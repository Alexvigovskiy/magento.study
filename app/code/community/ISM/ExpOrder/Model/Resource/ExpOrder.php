<?php

class ISM_ExpOrder_Model_Resource_ExpOrder extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct() {
        $this->_init('ism_exporder/exporder_entities', 'exporder_id');
    }

}
