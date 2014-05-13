<?php

class ISM_News_Block_Latest extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface {

    public function getNewsList() {
        $collection = Mage::getModel("news/news")->getCollection()->setOrder('publish_date', 'DESC')->addFilter('status = 1 AND publish_date >= "'.date("Y/m/d").'"');
        $collection->getSelect()->limit($this->getCount());
        Mage::dispatchEvent('widget_log', array('collection'=>$collection));
        return $collection;
        
    }

    public function getCount() {
        $size = $this->getData('news_count');
        return $size;
    }

}
