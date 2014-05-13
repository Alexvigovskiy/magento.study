<?php

class ISM_News_Block_News extends Mage_Core_Block_Template {

    public function getNews() {
        return Mage::getModel("news/news")->load($this->getRequest()->getParam('id'));
    }

    public function getNewsList() {
        $collection = Mage::getModel("news/news")->getCollection()->addFilter('status = 1 AND publish_date >= "' . date("Y/m/d") . '"');
        return $collection;
    }

    public function limitCharacter($string, $limit = 20, $suffix = ' . . .') {
        $string = strip_tags($string);
        if (strlen($string) < $limit) {
            return $string;
        }
        for ($i = $limit; $i >= 0; $i--) {
            $c = $string[$i];
            if ($c == ' ' OR $c == "\n") {
                return substr($string, 0, $i) . $suffix;
            }
        }
        return substr($string, 0, $limit) . $suffix;
    }

}
