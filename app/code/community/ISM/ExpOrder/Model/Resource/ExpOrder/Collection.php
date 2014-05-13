<?php

class ISM_ExpOrder_Model_Resource_ExpOrder_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('ism_exporder/exporder');
    }

}
