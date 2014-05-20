<?php

class ISM_NewstoreMembers_Model_Resource_Newstoremembers_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('ism_newstoremembers/newstoremembers');
    }

}
