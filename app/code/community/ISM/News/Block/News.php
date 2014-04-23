<?php
class ISM_News_Block_News extends Mage_Core_Block_Template
{
public function _prepareLayout()
{
return parent::_prepareLayout();
}
public function getNews()
{
if (!$this->hasData('news')) {
$this->setData('news', Mage::registry('news'));
}
return $this->getData('news');
}
public function getNewsList()
{
if (!$this->hasData('list')) {
$this->setData('list', Mage::registry('list'));
}
return $this->getData('list');
}
function limitCharacter($string, $limit = 20, $suffix = ' . . .')
{
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