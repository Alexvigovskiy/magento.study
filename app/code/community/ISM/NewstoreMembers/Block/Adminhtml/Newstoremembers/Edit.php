<?php

class ISM_NewstoreMembers_Block_Adminhtml_Newstoremembers_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'ism_newstoremembers';
        $this->_controller = 'adminhtml_newstoremembers';
        $this->_updateButton('save', 'label', 
                Mage::helper('ism_newstoremembers')->__('Save Member'));
        $this->_updateButton('delete', 'label', 
                Mage::helper('ism_newstoremembers')->__('Delete Member'));
    }

    public function getHeaderText() {
        if (Mage::registry('newstoremembers_data') 
                && Mage::registry('newstoremembers_data')->getId()) {
            return Mage::helper('ism_newstoremembers')->__(
                    "Edit Item '%s'", $this->htmlEscape(
                            Mage::registry('newstoremembers_data')->getTitle()));
        } else {
            return Mage::helper('ism_newstoremembers')->__('Add Member');
        }
    }

}
