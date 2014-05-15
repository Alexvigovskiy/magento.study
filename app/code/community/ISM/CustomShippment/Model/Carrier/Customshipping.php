<?php

class ISM_CustomShippment_Model_Carrier_Customshipping extends Mage_Shipping_Model_Carrier_Abstract {

    protected $_code = 'customshipping';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {

        if (!$this->getConfigFlag('active')) {
            return false;
        }
        $result = Mage::getModel('shipping/rate_result');
        $shippingPrice = '0.00';
        if ($shippingPrice !== false) {
            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethod($this->_code);
            $method->setMethodTitle($this->getConfigData('name'));
            $method->setPrice($shippingPrice);
            $method->setCost($shippingPrice);
            $result->append($method);
        }
        return $result;
    }

    /**
     * This method is used when viewing / listing Shipping Methods with Codes programmatically
     */
    public function getAllowedMethods() {
        return array($this->_code => $this->getConfigData('name'));
    }

}
