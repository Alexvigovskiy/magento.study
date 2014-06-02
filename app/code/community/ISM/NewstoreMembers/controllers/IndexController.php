<?php

class ISM_NewstoreMembers_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        //Validation for customer acount form
        Mage::helper('ism_newstoremembers')
                ->setMember($this->getRequest()->getPost(), 'customer');
        $this->_redirect('customer/account/edit');
    }

    public function cartAction() {
        //Validation for checkout
        Mage::helper('ism_newstoremembers')
                ->setMember($this->getRequest()->getPost(), 'checkout');
        $this->_redirect('checkout/cart');
    }

}
