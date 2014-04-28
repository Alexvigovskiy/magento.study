<?php
class Freaks_Quotes_Block_Content extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        $this->setTemplate('freaks/quotes/view.phtml');
    }
    
    public function getRowUrl(Freaks_Quotes_Model_Quote $quote)
    {
        return $this->getUrl('*/*/view', array(
            'id' => $quote->getId()
        ));
    }
    
    public function getCollection()
    {
        return Mage::getModel('freaks_quotes/quote')->getCollection();
    }
}
