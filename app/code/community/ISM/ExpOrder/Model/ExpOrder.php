<?php

class ISM_ExpOrder_Model_ExpOrder extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('ism_exporder/exporder');
    }

}
