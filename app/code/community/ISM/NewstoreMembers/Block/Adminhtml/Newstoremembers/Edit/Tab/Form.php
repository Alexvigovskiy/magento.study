<?php

class ISM_NewstoreMembers_Block_Adminhtml_Newstoremembers_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('newstoremembers_form', array(
            'legend' => Mage::helper('ism_newstoremembers')
                    ->__('Member information')
                ));

        $fieldset->addField('user_id', 'select', array(
            'label' => Mage::helper('ism_newstoremembers')->__('Member'),
            'name' => 'user_id',
            'required' => true,
            'class' => 'required-entry',
            'values' => Mage::helper('ism_newstoremembers')->getMembersValue(),
        ));
        $fieldset->addField('member_number', 'text', array(
            'label' => Mage::helper('ism_newstoremembers')->__('Member Number'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'member_number',
        ));
        $fieldset->addField('post_code', 'text', array(
            'label' => Mage::helper('ism_newstoremembers')->__('Post Code'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'post_code',
        ));
        $dateFormatIso = Mage::app()->getLocale()
                ->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $fieldset->addField('expire_date', 'date', array(
            'name' => 'expire_date',
            'label' => Mage::helper('ism_newstoremembers')->__('Expire date'),
            'title' => Mage::helper('ism_newstoremembers')->__('Expire date'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'required' => true,
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format' => $dateFormatIso
        ));

        if (Mage::getSingleton('adminhtml/session')->getNewstoremembersData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')
                    ->getNewstoremembersData());
            Mage::getSingleton('adminhtml/session')->setNewstoremembersData(null);
        } elseif (Mage::registry('newstoremembers_data')) {
            $form->setValues(Mage::registry('newstoremembers_data')->getData());
        }
        return parent::_prepareForm();
    }

}
