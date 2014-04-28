<?php
class Freaks_Quotes_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {
        $this->_blockGroup = 'freaks_quotes';
        $this->_mode = 'edit';
        $this->_controller = 'adminhtml';
        
        $quote_id = (int)$this->getRequest()->getParam($this->_objectId);
        if(!$quote_id) {
        //    Mage::throwException($this->__('Quote with this id does not exists'));
        }
        $quote = Mage::getModel('freaks_quotes/quote')->load($quote_id);
        Mage::register('current_quote', $quote);
        $this->_removeButton('reset');
    }
 
    public function getHeaderText()
    {
        $quote = Mage::registry('current_quote');
        if ($quote->getId()) { 
            return Mage::helper('freaks_quotes')->__("Edit Quote '%s'", $this->escapeHtml($quote->getName()));
        } else {
            return Mage::helper('freaks_quotes')->__("Add new Quote");
        }
    }
}
