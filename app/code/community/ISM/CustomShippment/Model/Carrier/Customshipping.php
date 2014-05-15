<?php

class ISM_CustomPayment_Model_PaymentMethod extends Mage_Payment_Model_Method_Cc {

    //Set payment method identifier
    protected $_code = 'ism_custompayment';
    //Is this payment method a gateway (online auth/charge) ?
    protected $_isGateway = true;
    //Can authorize online?
    protected $_canAuthorize = true;
    //Can capture funds online?
    protected $_canCapture = true;
    //Can capture partial amounts online?
    protected $_canCapturePartial = true;
    //Can refund online?
    protected $_canRefund = true;
    //Can void transactions online?
    protected $_canVoid = true;
    //Can use this payment method in administration panel?
    protected $_canUseInternal = true;
    //Can show this payment method as an option on checkout payment page?
    protected $_canUseCheckout = true;
    //Is this payment method suitable for multi-shipping checkout?
    protected $_canUseForMultishipping = true;
    //Can save credit card information for future processing?
    protected $_canSaveCc = true;

}
