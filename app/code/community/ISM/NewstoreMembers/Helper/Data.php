<?php

/**
 * Class ISM_NewstoreMembers_Helper_Data
 */
class ISM_NewstoreMembers_Helper_Data extends Mage_Core_Helper_Data {

    public function setMember($number) {

        $customer = Mage::getModel('customer/session')->getCustomer();
        $customerName = $customer->getFirstname() . " " . $customer->getLastname();
        $memberCollection = Mage::getModel('ism_newstoremembers/newstoremembers')->getCollection()->addFieldToFilter('member', $customerName);
        foreach ($memberCollection as $member) {
            if ($member->getStatus() == 0 && $member->getMemberNumber() == $number['member_number']) {
                $member->setStatus('1');
                $member->save();
                $customer->setGroupId('4');
                $customer->save();
            }
        }
    }

    public function getMemberNumber($customerName) {
        $memberCollection = Mage::getModel('ism_newstoremembers/newstoremembers')->getCollection()->addFieldToFilter('member', $customerName);
        foreach ($memberCollection as $member) {
            return $member->getMemberNumber();
        }
    }

}
