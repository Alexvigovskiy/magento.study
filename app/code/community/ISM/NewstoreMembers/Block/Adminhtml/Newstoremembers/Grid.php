<?php

class ISM_NewstoreMembers_Block_Adminhtml_Newstoremembers_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('newstoremembersGrid');
        $this->setDefaultSort('member_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('ism_newstoremembers/newstoremembers')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('member_id', array(
            'header' => Mage::helper('ism_newstoremembers')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'member_id',
        ));
        $this->addColumn('member', array(
            'header' => Mage::helper('ism_newstoremembers')->__('Member'),
            'align' => 'left',
            'index' => 'member',
        ));
        $this->addColumn('member_number', array(
            'header' => Mage::helper('ism_newstoremembers')->__('Member Number'),
            'align' => 'left',
            'index' => 'member_number',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('ism_newstoremembers')->__('In Membersip'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Yes',
                0 => 'No',
            ),
        ));
        $this->addColumn('action', array(
            'header' => Mage::helper('ism_newstoremembers')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('ism_newstoremembers')->__('Edit'),
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

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}
