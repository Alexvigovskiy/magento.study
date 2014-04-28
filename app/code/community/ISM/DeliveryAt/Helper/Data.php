<?php

class ISM_DeliveryAt_Helper_Data extends Mage_Core_Helper_Data {

    public function getValue($columname, $id) {
        try {
            return Mage::getModel('sales/order')->load($id)->getData($columname);
        } catch (Exception $e) {
            throw new Exception("This is an exception to stop the installer from completing");

            return null;
        }
    }

}
