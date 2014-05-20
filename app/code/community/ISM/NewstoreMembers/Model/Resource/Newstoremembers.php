<?php

class ISM_NewstoreMembers_Model_Resource_Newstoremembers extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct() {
        $this->_init('ism_newstoremembers/newstoremembers', 'member_id');
    }

}
