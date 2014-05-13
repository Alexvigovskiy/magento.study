<?php

class ISM_News_Block_Adminhtml_News_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'news';
        $this->_controller = 'adminhtml_news';
        $this->_updateButton('save', 'label', Mage::helper('news')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('news')->__('Delete Item'));
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function getHeaderText() {
        if (Mage::registry('news_data') && Mage::registry('news_data')->getId()) {
            return Mage::helper('news')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('news_data')->getTitle()));
        } else {
            return Mage::helper('news')->__('Add Item');
        }
    }

}
