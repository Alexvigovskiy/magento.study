<?php

class ISM_News_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewAction() {
        $news_id = $this->getRequest()->getParam('id');
        $news = Mage::getModel('news/news');

        if (!$news->hasData($news_id) && $news_id != "" && $news->hasData($news->load($news_id)->getData())) {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->_redirect('no-route');
        }
    }

}
