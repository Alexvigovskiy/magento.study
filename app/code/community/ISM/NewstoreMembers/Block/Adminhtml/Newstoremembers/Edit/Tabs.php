<?php

class ISM_NewstoreMembers_Block_Adminhtml_Newstoremembers_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('newstoremembers_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('ism_newstoremembers')->__('Member Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('ism_newstoremembers')->__('Member Information'),
            'title' => Mage::helper('ism_newstoremembers')->__('Member Information'),
            'content' => $this->getLayout()->createBlock('ism_newstoremembers/adminhtml_newstoremembers_edit_tab_form')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
