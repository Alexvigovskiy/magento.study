<?php

class ISM_News_Model_Observer {

    public function logCustomer(Varien_Event_Observer $observer) {
        $customer = $observer->getCustomer();
        Mage::log($customer->getName(), null, "my.log", true);
    }

    public function logCollection(Varien_Event_Observer $observer) {
        $collection = $observer->getData();
        Mage::log($collection, null, "coll.log", true);
    }

}
