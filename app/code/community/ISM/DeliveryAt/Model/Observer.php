<?php

class ISM_DeliveryAt_Model_Observer {

    public function checkoutControllerOnepageSaveShippingMethod(Varien_Event_Observer $observer) {
        $observer->getQuote()->setDeliverydate($observer->getRequest()->getPost('delivery_date'));
    }

    public function salesConvertQuoteToOrder(Varien_Event_Observer $observer) {
        $observer->getOrder()->setDeliverydate($observer->getQuote()->getDeliverydate());
    }

}
