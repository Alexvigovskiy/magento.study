<?php

class ISM_NewstoreMembers_Block_Adminhtml_Newstoremembers_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('newstoremembers_form', array('legend' => Mage::helper('ism_newstoremembers')->__('Member information')));

        $fieldset->addField('member', 'text', array(
            'label' => Mage::helper('ism_newstoremembers')->__('Member'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'member',
        ));
        $fieldset->addField('member_number', 'text', array(
            'label' => Mage::helper('ism_newstoremembers')->__('Member Number'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'member_number',
        ));
        
        if (Mage::getSingleton('adminhtml/session')->getNewstoremembersData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getNewstoremembersData());
            Mage::getSingleton('adminhtml/session')->setNewstoremembersData(null);
        } elseif (Mage::registry('newstoremembers_data')) {
            $form->setValues(Mage::registry('newstoremembers_data')->getData());
        }
        return parent::_prepareForm();
    }

}
