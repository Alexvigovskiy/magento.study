<?php

class ISM_NewstoreMembers_Block_Adminhtml_Newstoremembers extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_newstoremembers';
        $this->_blockGroup = 'ism_newstoremembers';
        $this->_headerText = Mage::helper('news')->__('Members Manager');
        $this->_addButtonLabel = Mage::helper('news')->__('Add New Member');
        parent::__construct();
    }

}
