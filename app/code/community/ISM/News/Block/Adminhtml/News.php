<?php

class ISM_News_Block_Adminhtml_News extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_news';
        $this->_blockGroup = 'news';
        $this->_headerText = Mage::helper('news')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('news')->__('Add Item');
        parent::__construct();
    }

}
