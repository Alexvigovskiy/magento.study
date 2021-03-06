<?php

class ISM_News_Block_Adminhtml_News_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('newsGrid');
        $this->setDefaultSort('news_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('news/news')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('news_id', array(
            'header' => Mage::helper('news')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'news_id',
        ));
        $this->addColumn('title', array(
            'header' => Mage::helper('news')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $this->addColumn('created_time', array(
            'header' => Mage::helper('news')->__('Creation Time'),
            'align' => 'left',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'created_time',
        ));

        $this->addColumn('update_time', array(
            'header' => Mage::helper('news')->__('Update Time'),
            'align' => 'left',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'update_time',
        ));
        $this->addColumn('publish_date', array(
            'header' => Mage::helper('news')->__('Publish date'),
            'align' => 'left',
            'width' => '120px',
            'type' => 'date',
            'default' => '--',
            'index' => 'publish_date',
        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('news')->__('Published'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Yes',
                2 => 'No',
            ),
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('news')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('news')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('news_id');
        $this->getMassactionBlock()->setFormFieldName('news');
        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('news')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('news')->__('Are you sure?')
        ));
        $statuses = Mage::getSingleton('news/status')->getOptionArray();
        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('news')->__('Change Publish status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current'
                => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('news')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
