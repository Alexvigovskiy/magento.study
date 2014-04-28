<?php
class Freaks_Quotes_Adminhtml_QuotesController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Quotes'));

        $this->loadLayout();
        $this->_setActiveMenu('freaks_quotes');
        $this->_addBreadcrumb(Mage::helper('freaks_quotes')->__('Quotes'), Mage::helper('freaks_quotes')->__('Quotes'));
        $this->renderLayout();
    }
    
    public function newAction()
    {
        $this->_title($this->__('Add new quote'));
        $this->loadLayout();
        $this->_setActiveMenu('freaks_quotes');
        $this->_addBreadcrumb(Mage::helper('freaks_quotes')->__('Add new quote'), Mage::helper('freaks_quotes')->__('Add new quote'));
        $this->renderLayout();
    }
    
    public function editAction()
    {
        $this->_title($this->__('Edit quote'));

        $this->loadLayout();
        $this->_setActiveMenu('freaks_quotes');
        $this->_addBreadcrumb(Mage::helper('freaks_quotes')->__('Edit quote'), Mage::helper('freaks_quotes')->__('Edit Quote'));
        $this->renderLayout();
    }
    
    public function deleteAction()
    {
        $tipId = $this->getRequest()->getParam('id', false);
 
        try {
            Mage::getModel('freaks_quotes/quote')->setId($tipId)->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('freaks_quotes')->__('Quote successfully deleted'));
            
            return $this->_redirect('*/*/');
        } catch (Mage_Core_Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($this->__('Somethings went wrong'));
        }
 
        $this->_redirectReferer();
    }

    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        if (!empty($data)) {
            try {
                Mage::getModel('freaks_quotes/quote')->setData($data)
                    ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('freaks_quotes')->__('Quote successfully saved'));
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($this->__('Somethings went wrong'));
            }
        }
        return $this->_redirect('*/*/');
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('freaks_quotes/adminhtml_quotes_grid')->toHtml()
        );
    }
}
