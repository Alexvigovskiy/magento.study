<?php
class ISM_News_IndexController extends Mage_Core_Controller_Front_Action
{
    
public function indexAction()
{
$resource = Mage::getSingleton('core/resource');
$read = $resource->getConnection('core_read');
$newsTable = $resource->getTableName('news');
$select = $read->select()->from($newsTable, array('news_id', 'title', 'filename', 'announce', 'text', 'status'))->where('status', 1)->order('created_time DESC');
$news = $read->fetchAll($select);
Mage::register('list', $news);
$this->loadLayout();
$this->renderLayout();
}

public function viewAction()
{
$news_id = $this->getRequest()->getParam('id');
if ($news_id != null && $news_id != '') {
$news = Mage::getModel('news/news')->load($news_id)->getData();
} else {
$news = null;
}
if ($news == null) {
$resource = Mage::getSingleton('core/resource');
$read = $resource->getConnection('core_read');
$newsTable = $resource->getTableName('news');
$select = $read->select()->from($newsTable, array('news_id', 'filename', 'title', 'announce', 'text', 'status', 'update_time'))->where('status', 1)->order('created_time DESC');
$news = $read->fetchRow($select);
}
Mage::register('news', $news);
$this->loadLayout();
$this->renderLayout();
}
}