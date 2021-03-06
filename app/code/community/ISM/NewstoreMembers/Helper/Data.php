<?php

/**
 * Class ISM_NewstoreMembers_Helper_Data
 */
class ISM_NewstoreMembers_Helper_Data extends Mage_Core_Helper_Data {

    public function setMember($number, $state) {
        //Customer validation to Newstore Members
        $customerSession = Mage::getModel('customer/session');
        $checkoutSession = Mage::getModel('checkout/session');
        //GetCustomer
        $customer = $customerSession->getCustomer();
        $address = Mage::getModel('customer/address')
                ->load($customer->getDefaultBilling());
        if ($state == 'customer') {
            $message = $customerSession->setCustomer($customer);
        }
        if ($state == 'checkout') {
            $message = $checkoutSession;
        }
        $customerId = $customer->getEntityId();
        //Get member collection to validate
        $member = Mage::getModel('ism_newstoremembers/newstoremembers')
                ->load($customerId, 'user_id');
        //Check for availability
        if ($member->getStatus() != 0) {
            $message->addError($this->__(
                    'Your acoount is already added to Newstore Members'
                    ));
            return;
        }
        //Check member number
        if ($member->getMemberNumber() != $number['member_number']) {
            $message->addError($this->__(
                    'Your individual member number is invalid!'
                    . ' Please check the entered number, and click on "confirm"'
                    . ' again.'
                    ));
            return;
        }
        //Check postcode
        if ($member->getPostCode() != $address->getPostcode()) {
            $message->addError($this->__(
                    'Sorry, but your postcode is invalid.'
                    ));
            return;
        }
        //Check expire date
        if ($member->getExpireDate() < now()) {
            $message->addError($this->__(
                    'Sorry, but your individual member number is expired.'
                    . ' Please contuct us, about your problem.'
                    ));
            return;
        }
        //Set status to "Yes"
        $member->setStatus('1');
        $member->save();
        //Set Customer Group
        $customer->setGroupId('4');
        $customer->save();
        $message->addSuccess($this->__(
                'Congratulations! You are valid member now.'
                ));
    }

    public function getMemberNumber($customerId) {
        //Getting member number to adminhtml order page
        return Mage::getModel('ism_newstoremembers/newstoremembers')
                ->load($customerId, 'user_id')->getMemberNumber();
    }

    public function getMembersValue() {
        //Getting list of customers with their id 
        $collection = Mage::getModel('customer/customer')
                ->getCollection()->addAttributeToSelect('*');
        foreach ($collection as $customer) {
            $customerArray = $customer->toArray();
            $member = $customerArray['firstname'] . " " 
                    . $customerArray['lastname'];
            $array['value'] = $customerArray['entity_id'];
            $array['label'] = $member;
            $valuesArray[] = $array;
        }
        return $valuesArray;
    }
    public function isMatch($model, $field, $value) {
        //Check value for uniqueness
        $model->load($value, $field);
        if (empty($model->getData())) {
            return true;
        }
        return false;
    }

}
