<?php

class ISM_NewstoreMembers_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        Mage::helper('ism_newstoremembers')->setMember($this->getRequest()->getPost());
        $this->_redirect('customer/account/edit');
    }

}
