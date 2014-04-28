<?php
class Freaks_Quotes_Block_Quote_Content extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        $this->setTemplate('freaks/quotes/quote/view.phtml');
    }
    
    public function getQuote()
    {
        return Mage::getModel('freaks_quotes/quote')->load($this->getQuoteId());
    }
}
